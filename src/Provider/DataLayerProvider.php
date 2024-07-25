<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Provider;

use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\DataLayer\PostDataCollector;
use Inpsyde\GoogleTagManager\DataLayer\SearchDataCollector;
use Inpsyde\GoogleTagManager\DataLayer\SiteInfoDataCollector;
use Inpsyde\GoogleTagManager\DataLayer\UserDataCollector;
use Inpsyde\GoogleTagManager\Service\DataCollectorRegistry;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\Modularity\Module\ExtendingModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;

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
            DataLayer::class => static function (ContainerInterface $container): DataLayer {
                return DataLayer::new(
                    $container->get(SettingsRepository::class),
                    $container->get(DataCollectorRegistry::class),
                );
            },
            UserDataCollector::class => static function (ContainerInterface $container): UserDataCollector {
                $settingsRepository = $container->get(SettingsRepository::class);

                return UserDataCollector::new($settingsRepository->option(UserDataCollector::ID));
            },
            SiteInfoDataCollector::class => static function (ContainerInterface $container): SiteInfoDataCollector {
                $settingsRepository = $container->get(SettingsRepository::class);

                return SiteInfoDataCollector::new($settingsRepository->option(SiteInfoDataCollector::ID));
            },
            PostDataCollector::class => static function (ContainerInterface $container): PostDataCollector {
                $settingsRepository = $container->get(SettingsRepository::class);

                return PostDataCollector::new($settingsRepository->option(PostDataCollector::ID));
            },
            SearchDataCollector::class => static function (ContainerInterface $container): SearchDataCollector {
                $settingsRepository = $container->get(SettingsRepository::class);

                return SearchDataCollector::new($settingsRepository->option(SearchDataCollector::ID));
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
                    UserDataCollector::class,
                    SiteInfoDataCollector::class,
                    PostDataCollector::class,
                    SearchDataCollector::class,
                ];
                foreach ($collectors as $collector) {
                    $registry->register($container->get($collector));
                }

                return $registry;
            },
        ];
    }
}
