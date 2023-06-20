<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Provider;

use Inpsyde\GoogleTagManager\Event\GtmScriptTagRendererEvent;
use Inpsyde\GoogleTagManager\Event\NoscriptTagRendererEvent;
use Inpsyde\GoogleTagManager\Renderer\DataLayerRenderer;
use Inpsyde\GoogleTagManager\Renderer\GtmScriptTagRenderer;
use Inpsyde\GoogleTagManager\Renderer\NoscriptTagRenderer;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;

/**
 * Class RendererProvider
 *
 * @package Inpsyde\GoogleTagManager\App\Provider
 */
final class RendererProvider implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    /**
     * {@inheritDoc}
     * phpcs:disable Inpsyde.CodeQuality.LineLength.TooLong
     */
    public function services(): array
    {
        return [
            'Renderer.GtmScriptTagRenderer' => static function (ContainerInterface $container): GtmScriptTagRenderer {
                return new GtmScriptTagRenderer($container->get('DataLayer'));
            },
            'Renderer.DataLayerRenderer' => static function (ContainerInterface $container): DataLayerRenderer {
                return new DataLayerRenderer($container->get('DataLayer'));
            },
            'Renderer.NoscriptTagRenderer' => static function (ContainerInterface $container): NoscriptTagRenderer {
                return new NoscriptTagRenderer($container->get('DataLayer'));
            },
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        if (is_admin()) {
            return false;
        }

        add_action(
            GtmScriptTagRendererEvent::ACTION_BEFORE_SCRIPT,
            [$container->get('Renderer.DataLayerRenderer'), 'render']
        );

        add_action(
            'wp_head',
            [$container->get('Renderer.GtmScriptTagRenderer'), 'render']
        );

        add_action(
            NoscriptTagRendererEvent::ACTION_RENDER,
            [$container->get('Renderer.NoscriptTagRenderer'), 'render']
        );

        add_action(
            'wp_body_open',
            [$container->get('Renderer.NoscriptTagRenderer'), 'renderAtBodyStart'],
            -PHP_INT_MAX
        );

        return true;
    }
}
