<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\DataLayer;

use Inpsyde\GoogleTagManager\Core\BootableProviderInterface;
use Inpsyde\GoogleTagManager\DataLayer\Site\SiteInfoDataCollector;
use Inpsyde\GoogleTagManager\DataLayer\User\UserDataCollector;
use Inpsyde\GoogleTagManager\Settings\SettingsPage;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer
 */
final class Provider implements ServiceProviderInterface, BootableProviderInterface
{

    /**
     * @param Container $plugin
     */
    public function register(Container $plugin)
    {

        $plugin->offsetSet(
            'DataLayer',
            function (Container $plugin): DataLayer {

                return new DataLayer($plugin[ 'Settings.SettingsRepository' ]);
            }
        );

        $plugin->offsetSet(
            'DataLayer.User.UserDataCollector',
            function (Container $plugin): UserDataCollector {

                return new UserDataCollector($plugin[ 'Settings.SettingsRepository' ]);
            }
        );

        $plugin->offsetSet(
            'DataLayer.Site.SiteInfoDataCollector',
            function (Container $plugin): SiteInfoDataCollector {

                return new SiteInfoDataCollector($plugin[ 'Settings.SettingsRepository' ]);
            }
        );
    }

    /**
     * @param Container $plugin
     */
    public function boot(Container $plugin)
    {

        $plugin->extend(
            'DataLayer',
            function (DataLayer $data_layer, Container $plugin): DataLayer {

                $data_layer->addData($plugin[ 'DataLayer.User.UserDataCollector' ]);
                $data_layer->addData($plugin[ 'DataLayer.Site.SiteInfoDataCollector' ]);

                return $data_layer;
            }
        );

        if (!is_admin()) {
            return;
        }

        $factory  = $plugin[ 'ChriCo.Fields.ElementFactory' ];
        $settings = [
            $plugin[ 'DataLayer' ]->settingsSpec(),
            $plugin[ 'DataLayer.User.UserDataCollector' ]->settingsSpec(),
            $plugin[ 'DataLayer.Site.SiteInfoDataCollector' ]->settingsSpec(),
        ];

        foreach ($settings as $spec) {
            $plugin->extend(
                'Settings.Page',
                function (SettingsPage $page) use ($factory, $spec): SettingsPage {
                    $page->addElement(
                        $factory->create($spec),
                        $spec[ 'filters' ] ?? [],
                        $spec[ 'validators' ] ?? []
                    );

                    return $page;
                }
            );
        }
    }
}
