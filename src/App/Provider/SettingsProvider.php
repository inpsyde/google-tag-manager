<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\App\Provider;

use Inpsyde\GoogleTagManager\App\BootableProvider;
use Inpsyde\GoogleTagManager\GoogleTagManager;
use Inpsyde\GoogleTagManager\Settings\SettingsPage;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\View\TabbedSettingsPageView;

/**
 * @package Inpsyde\GoogleTagManager\Settings
 */
final class SettingsProvider implements BootableProvider
{

    /**
     * @param GoogleTagManager $plugin
     *
     * @throws \Inpsyde\GoogleTagManager\Exception\AlreadyBootedException
     */
    public function register(GoogleTagManager $plugin)
    {
        $plugin->set(
            'Settings.SettingsRepository',
            function (GoogleTagManager $plugin): SettingsRepository {
                return new SettingsRepository($plugin->get('config')->get('plugin.textdomain'));
            }
        );

        $plugin->set(
            'Settings.Page',
            function (GoogleTagManager $plugin): SettingsPage {
                return new SettingsPage(
                    new TabbedSettingsPageView($plugin->get('config')),
                    $plugin->get('Settings.SettingsRepository')
                );
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
        if (is_admin()) {
            add_action('admin_menu', [$plugin->get('Settings.Page'), 'register']);
        }
    }
}
