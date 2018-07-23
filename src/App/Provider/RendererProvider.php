<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\App\Provider;

use Inpsyde\GoogleTagManager\App\BootableProvider;
use Inpsyde\GoogleTagManager\Event\GtmScriptTagRendererEvent;
use Inpsyde\GoogleTagManager\Event\NoscriptTagRendererEvent;
use Inpsyde\GoogleTagManager\GoogleTagManager;
use Inpsyde\GoogleTagManager\Renderer\DataLayerRenderer;
use Inpsyde\GoogleTagManager\Renderer\GtmScriptTagRenderer;
use Inpsyde\GoogleTagManager\Renderer\NoscriptTagRenderer;

/**
 * Class RendererProvider
 *
 * @package Inpsyde\GoogleTagManager\App\Provider
 */
final class RendererProvider implements BootableProvider
{

    /**
     * @param GoogleTagManager $plugin
     *
     * @throws \Inpsyde\GoogleTagManager\Exception\AlreadyBootedException
     */
    public function register(GoogleTagManager $plugin)
    {
        $plugin->set(
            'Renderer.GtmScriptTagRenderer',
            function (GoogleTagManager $plugin): GtmScriptTagRenderer {
                return new GtmScriptTagRenderer($plugin->get('DataLayer'));
            }
        );

        $plugin->set(
            'Renderer.DataLayerRenderer',
            function (GoogleTagManager $plugin): DataLayerRenderer {
                return new DataLayerRenderer($plugin->get('DataLayer'));
            }
        );

        $plugin->set(
            'Renderer.NoscriptTagRenderer',
            function (GoogleTagManager $plugin): NoscriptTagRenderer {
                return new NoscriptTagRenderer($plugin->get('DataLayer'));
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
        if (! is_admin()) {
            add_action(
                GtmScriptTagRendererEvent::ACTION_BEFORE_SCRIPT,
                [$plugin->get('Renderer.DataLayerRenderer'), 'render']
            );

            add_action(
                'wp_head',
                [$plugin->get('Renderer.GtmScriptTagRenderer'), 'render']
            );

            add_action(
                NoscriptTagRendererEvent::ACTION_RENDER,
                [$plugin->get('Renderer.NoscriptTagRenderer'), 'render']
            );

            add_action(
                'body_class',
                [$plugin->get('Renderer.NoscriptTagRenderer'), 'renderAtBodyStart'],
                PHP_INT_MAX
            );
        }
    }
}
