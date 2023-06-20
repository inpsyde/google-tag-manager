<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Provider;

use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\DataLayer\Site\SiteInfoDataCollector;
use Inpsyde\GoogleTagManager\DataLayer\User\UserDataCollector;
use Inpsyde\GoogleTagManager\Settings\SettingsPage;
use Inpsyde\Modularity\Module\ExecutableModule;
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
                return new DataLayer($container->get('Settings.SettingsRepository'));
            },
            'DataLayer.User.UserDataCollector' => static function (ContainerInterface $container): UserDataCollector {
                return new UserDataCollector($container->get('Settings.SettingsRepository'));
            },
            'DataLayer.Site.SiteInfoDataCollector' => static function (ContainerInterface $container): SiteInfoDataCollector {
                return new SiteInfoDataCollector($container->get('Settings.SettingsRepository'));
            },
        ];
    }

    public function extensions(): array
    {
        return [
            'Settings.Page' => static function (SettingsPage $page, ContainerInterface $container): SettingsPage {
                $settings = [
                    $container->get('DataLayer')->settingsSpec(),
                    $container->get('DataLayer.User.UserDataCollector')->settingsSpec(),
                    $container->get('DataLayer.Site.SiteInfoDataCollector')->settingsSpec(),
                ];

                foreach ($settings as $spec) {
                    $page->addElement(createElement($spec));
                }

                return $page;
            },
            'DataLayer' => static function (DataLayer $dataLayer, ContainerInterface $container): DataLayer {
                $dataLayer->addData($container->get('DataLayer.User.UserDataCollector'));
                $dataLayer->addData($container->get('DataLayer.Site.SiteInfoDataCollector'));

                return $dataLayer;
            },
        ];
    }
}
