<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Provider;

use Inpsyde\Assets\Asset;
use Inpsyde\Assets\AssetManager;
use Inpsyde\Assets\Script;
use Inpsyde\Assets\Style;
use Inpsyde\GoogleTagManager\GoogleTagManager;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Package;
use Inpsyde\Modularity\Properties\PluginProperties;
use Psr\Container\ContainerInterface;

/**
 * @package Inpsyde\GoogleTagManager\App\Provider
 */
final class AssetProvider implements ExecutableModule
{
    use ModuleClassNameIdTrait;

    /**
     * @param GoogleTagManager $plugin
     */
    public function run(ContainerInterface $container): bool
    {
        add_action(
            AssetManager::ACTION_SETUP,
            static function (AssetManager $manager) use ($container) {
                /** @var PluginProperties $properties */
                $properties = $container->get(Package::PROPERTIES);

                $assetUrl = $properties->baseUrl() . '/assets/';
                $manager->register(
                    (new Script(
                        'inpsyde-google-tag-manager-admin',
                        $assetUrl . 'inpsyde-google-tag-manager-admin.js',
                        Asset::BACKEND
                    ))->withDependencies('jquery-ui-tabs'),
                    (new Style(
                        'inpsyde-google-tag-manager-admin',
                        $assetUrl . 'inpsyde-google-tag-manager-admin.css',
                        Asset::BACKEND
                    ))
                );
            }
        );

        return true;
    }
}
