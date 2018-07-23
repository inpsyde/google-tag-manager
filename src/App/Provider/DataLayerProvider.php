<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\App\Provider;

use Inpsyde\GoogleTagManager\App\BootableProvider;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\DataLayer\Site\SiteInfoDataCollector;
use Inpsyde\GoogleTagManager\DataLayer\User\UserDataCollector;
use Inpsyde\GoogleTagManager\GoogleTagManager;

/**
 * @package Inpsyde\GoogleTagManager\App\Provider
 */
final class DataLayerProvider implements BootableProvider
{

    /**
     * @param GoogleTagManager $plugin
     *
     * @throws \Inpsyde\GoogleTagManager\Exception\AlreadyBootedException
     */
    public function register(GoogleTagManager $plugin)
    {
        $plugin->set(
            'DataLayer',
            function (GoogleTagManager $plugin): DataLayer {
                return new DataLayer($plugin->get('Settings.SettingsRepository'));
            }
        );

        $plugin->set(
            'DataLayer.User.UserDataCollector',
            function (GoogleTagManager $plugin): UserDataCollector {
                return new UserDataCollector($plugin->get('Settings.SettingsRepository'));
            }
        );

        $plugin->set(
            'DataLayer.Site.SiteInfoDataCollector',
            function (GoogleTagManager $plugin): SiteInfoDataCollector {
                return new SiteInfoDataCollector($plugin->get('Settings.SettingsRepository'));
            }
        );
    }

    /**
     * @param GoogleTagManager $plugin
     *
     * @throws \Inpsyde\GoogleTagManager\Exception\NotFoundException
     */
    public function boot(GoogleTagManager $plugin)
    {
        $dataLayer = $plugin->get('DataLayer');
        $dataLayer->addData($plugin->get('DataLayer.User.UserDataCollector'));
        $dataLayer->addData($plugin->get('DataLayer.Site.SiteInfoDataCollector'));

        if (! is_admin()) {
            return;
        }

        $factory = $plugin->get('ChriCo.Fields.ElementFactory');
        $settings = [
            $plugin->get('DataLayer')->settingsSpec(),
            $plugin->get('DataLayer.User.UserDataCollector')->settingsSpec(),
            $plugin->get('DataLayer.Site.SiteInfoDataCollector')->settingsSpec(),
        ];

        $settingsPage = $plugin->get('Settings.Page');
        foreach ($settings as $spec) {
            $settingsPage->addElement(
                $factory->create($spec),
                $spec['filters'] ?? [],
                $spec['validators'] ?? []
            );
        }
    }
}
