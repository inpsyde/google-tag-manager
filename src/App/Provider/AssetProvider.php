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
                $manager->register(
                    ...AssetFactory::createFromArray(
                        [
                            [
                                'handle' => 'inpsyde-google-tag-manager',
                                'type' => Script::class,
                                'location' => Asset::BACKEND,
                                'url' => $config->get('assets.js.url').'admin'.$config->get('assets.suffix').'.js',
                                'dependencies' => ['jquery-ui-tabs'],
                                'version' => $config->get('plugin.version'),
                            ],
                            [
                                'handle' => 'inpsyde-google-tag-manager',
                                'type' => Style::class,
                                'location' => Asset::BACKEND,
                                'url' => $config->get('assets.css.url').'admin'.$config->get('assets.suffix').'.css',
                                'version' => $config->get('plugin.version'),
                            ],
                        ]
                    )
                );
            }
        );
    }
}
