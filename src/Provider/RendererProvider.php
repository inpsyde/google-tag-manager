<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Provider;

use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
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
            GtmScriptTagRenderer::class => static function (ContainerInterface $container): GtmScriptTagRenderer {
                return GtmScriptTagRenderer::new($container->get(DataLayer::class));
            },
            DataLayerRenderer::class => static function (ContainerInterface $container): DataLayerRenderer {
                return DataLayerRenderer::new($container->get(DataLayer::class));
            },
            NoscriptTagRenderer::class => static function (ContainerInterface $container): NoscriptTagRenderer {
                return NoscriptTagRenderer::new($container->get(DataLayer::class));
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
            static fn () => $container->get(DataLayerRenderer::class)->render()
        );

        add_action(
            'wp_head',
            static fn () => $container->get(GtmScriptTagRenderer::class)->render()
        );

        add_action(
            NoscriptTagRendererEvent::ACTION_RENDER,
            static fn () => $container->get(NoscriptTagRenderer::class)->render()
        );

        add_action(
            'wp_body_open',
            static fn () => $container->get(NoscriptTagRenderer::class)->renderAtBodyStart(),
            -PHP_INT_MAX
        );

        return true;
    }
}
