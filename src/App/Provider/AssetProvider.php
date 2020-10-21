<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\App\Provider;

use Inpsyde\Assets\Asset;
use Inpsyde\Assets\AssetFactory;
use Inpsyde\Assets\AssetManager;
use Inpsyde\Assets\Script;
use Inpsyde\Assets\Style;
use Inpsyde\GoogleTagManager\App\PluginConfig;
use Inpsyde\GoogleTagManager\GoogleTagManager;

/**
 * @package Inpsyde\GoogleTagManager\App\Provider
 */
final class AssetProvider implements \Inpsyde\GoogleTagManager\App\Provider
{

    /**
     * @param GoogleTagManager $plugin
     *
     * @throws \Inpsyde\GoogleTagManager\Exception\NotFoundException
     */
    public function register(GoogleTagManager $plugin)
    {
        /** @var PluginConfig $config */
        $config = $plugin->get('config');
        add_action(
            AssetManager::ACTION_SETUP,
            function (AssetManager $manager) use ($config) {
                $assetUrl = $config->get('assets.url');
                $manager->register(
                    (new Script(
                        'inpsyde-google-tag-manager-admin',
                        $assetUrl.'inpsyde-google-tag-manager-admin.js',
                        Asset::BACKEND
                    ))->withDependencies('jquery-ui-tabs'),
                    (new Style(
                        'inpsyde-google-tag-manager-admin',
                        $assetUrl.'inpsyde-google-tag-manager-admin.css',
                        Asset::BACKEND
                    ))
                );
            }
        );
    }
}
