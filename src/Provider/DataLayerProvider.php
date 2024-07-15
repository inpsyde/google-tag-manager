<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Provider;

use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\DataLayer\PostDataCollector;
use Inpsyde\GoogleTagManager\DataLayer\SearchDataCollector;
use Inpsyde\GoogleTagManager\DataLayer\SiteInfoDataCollector;
use Inpsyde\GoogleTagManager\DataLayer\UserDataCollector;
use Inpsyde\GoogleTagManager\Service\DataCollectorRegistry;
use Inpsyde\GoogleTagManager\Settings\SettingsPage;
use Inpsyde\Modularity\Module\ExtendingModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;

use function ChriCo\Fields\createElement;

/**
 * @package Inpsyde\GoogleTagManager\App\Provider
 */
final class DataLayerProvider implements ServiceModule, ExtendingModule
{
    use ModuleClassNameIdTrait;

    /**
     * {@inheritDoc}
     * phpcs:disable Inpsyde.CodeQuality.LineLength.TooLong
     */
    public function services(): array
    {
        return [
            'DataLayer' => static function (ContainerInterface $container): DataLayer {
                return new DataLayer(
                    $container->get('Settings.SettingsRepository'),
                    $container->get(DataCollectorRegistry::class),
                );
            },
            'DataLayer.UserDataCollector' => static function (ContainerInterface $container): UserDataCollector {
                $settingsRepository = $container->get('Settings.SettingsRepository');

                return new UserDataCollector($settingsRepository->option(UserDataCollector::ID));
            },
            'DataLayer.SiteInfoDataCollector' => static function (ContainerInterface $container): SiteInfoDataCollector {
                $settingsRepository = $container->get('Settings.SettingsRepository');

                return new SiteInfoDataCollector($settingsRepository->option(SiteInfoDataCollector::ID));
            },
            'DataLayer.PostDataCollector' => static function (ContainerInterface $container): PostDataCollector {
                $settingsRepository = $container->get('Settings.SettingsRepository');

                return new PostDataCollector($settingsRepository->option(PostDataCollector::ID));
            },
            'DataLayer.SearchDataCollector' => static function (ContainerInterface $container): SearchDataCollector {
                $settingsRepository = $container->get('Settings.SettingsRepository');

                return new SearchDataCollector($settingsRepository->option(SearchDataCollector::ID));
            },
        ];
    }

    public function extensions(): array
    {
        return [
            DataCollectorRegistry::class => static function (
                DataCollectorRegistry $registry,
                ContainerInterface $container
            ): DataCollectorRegistry {
                $collectors = [
                    'DataLayer.UserDataCollector',
                    'DataLayer.SiteInfoDataCollector',
                    'DataLayer.PostDataCollector',
                    'DataLayer.SearchDataCollector',
                ];
                foreach ($collectors as $collector) {
                    $registry->register($container->get($collector));
                }

                return $registry;
            },
            'Settings.Page' => static function (SettingsPage $page, ContainerInterface $container): SettingsPage {
                $dataLayer = $container->get('DataLayer');
                $page->addElement(createElement($dataLayer->settingsSpec()));

                $collectors = $container->get(DataCollectorRegistry::class)->allFormFields();
                foreach ($collectors as $settingsSpec) {
                    $page->addElement(createElement($settingsSpec));
                }

                return $page;
            },
        ];
    }
}
