<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Provider;

use Inpsyde\GoogleTagManager\Settings\SettingsPage;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\View\TabbedSettingsPageView;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Inpsyde\Modularity\Package;
use Inpsyde\Modularity\Properties\PluginProperties;
use Psr\Container\ContainerInterface;

/**
 * @package Inpsyde\GoogleTagManager\Settings
 */
final class SettingsProvider implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            // phpcs:disable Inpsyde.CodeQuality.LineLength.TooLong
            'Settings.SettingsRepository' => static function (ContainerInterface $container): SettingsRepository {
                /** @var PluginProperties $properties */
                $properties = $container->get(Package::PROPERTIES);

                return new SettingsRepository($properties->textDomain());
            },
            'Settings.Page' => static function (ContainerInterface $container): SettingsPage {
                $properties = $container->get(Package::PROPERTIES);

                return new SettingsPage(
                    new TabbedSettingsPageView($properties),
                    $container->get('Settings.SettingsRepository')
                );
            },
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        if (!is_admin()) {
            return false;
        }

        add_action('admin_menu', [$container->get('Settings.Page'), 'register']);

        return true;
    }
}
