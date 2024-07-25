<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Provider;

use Inpsyde\GoogleTagManager\GoogleTagManager;
use Inpsyde\GoogleTagManager\Service\RestEndpointRegistry;
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
     * phpcs:disable Inpsyde.CodeQuality.LineLength.TooLong
     */
    public function run(ContainerInterface $container): bool
    {
        add_action('admin_head', static function () use ($container): void {
            if (!function_exists('get_current_screen')) {
                return;
            }

            /** @var PluginProperties $properties */
            $properties = $container->get(Package::PROPERTIES);

            $screen = get_current_screen();
            if ($screen === null) {
                return;
            }
            if ($screen->base !== 'settings_page_' . $properties->baseName()) {
                return;
            }

            $manifest = (array) require $properties->basePath()
                . '/assets/inpsyde-google-tag-manager-settings.asset.php';
            $dependencies = $manifest['dependencies'] ?? [];
            $version = $manifest['version'] ?? $properties->version();

            $assetUrl = $properties->baseUrl() . 'assets/';
            wp_register_script(
                'inpsyde-google-tag-manager-settings',
                $assetUrl . 'inpsyde-google-tag-manager-settings.js',
                $dependencies,
                $version,
                ['in_footer' => false]
            );

            wp_localize_script(
                'inpsyde-google-tag-manager-settings',
                'InpsydeGoogleTagManager',
                [
                    'Rest' => [
                        'namespace' => RestEndpointRegistry::NAMESPACE,
                    ],
                    'Entities' => array_values($container->get(RestEndpointRegistry::class)->entities()),
                ]
            );

            wp_enqueue_script('inpsyde-google-tag-manager-settings');
            wp_enqueue_style('wp-components');
            wp_enqueue_style(
                'inpsyde-google-tag-manager-settings-css',
                $assetUrl . 'inpsyde-google-tag-manager-settings.css',
                [],
                $version,
            );
        });

        return true;
    }
}
