<?php # -*- coding: utf-8 -*-

/**
 * Plugin Name: Inpsyde Google Tag Manager
 * Description: Adds the Google Tag Manager container snippet to your site and populates the Google Tag Manager Data Layer.
 * Plugin URI:  https://wordpress.org/plugins/inpsyde-google-tag-manager
 * Version:     2.0.0
 * Author:      Inpsyde GmbH
 * Author URI:  https://inpsyde.com
 * Text Domain: inpsyde-google-tag-manager
 */

namespace Inpsyde\GoogleTagManager;


use Inpsyde\GoogleTagManager\Event\LogEvent;
use Inpsyde\Modularity\Package;
use Inpsyde\Modularity\Properties\PluginProperties;

if (!function_exists('add_filter')) {
    return;
}

/**
 * @return Package
 * @throws \Exception
 */
function plugin(): Package
{
    /** @var null|Package $package */
    static $package;

    if (!$package) {
        $properties = PluginProperties::new(__FILE__);
        $package = Package::new($properties);
        $package
            ->addModule(new App\Provider\AssetProvider())
            ->addModule(new App\Provider\DataLayerProvider())
            ->addModule(new App\Provider\RendererProvider())
            ->addModule(new App\Provider\SettingsProvider());
    }

    return $package;
}

add_action(
    'plugins_loaded',
    static function (): bool {
        try {
            load_plugin_textdomain('inpsyde-google-tag-manager');
            if (!checkPluginRequirements()) {
                return false;
            }
            plugin()->boot();
        } catch (\Throwable $exception) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                throw $exception;
            }
            do_action(LogEvent::ACTION, 'critical', $exception);

            return false;
        }

        return true;
    }
);

do_action(
    plugin()->hookName(Package::ACTION_FAILED_BOOT),
    /**
     * Display an error message in the WP admin.
     *
     * @param \Throwable $exception
     */
    static function (\Throwable $exception): void {
        $message = sprintf(
            '<strong>Error:</strong> %s <br><pre>%s</pre>',
            $exception->getMessage(),
            $exception->getTraceAsString()
        );

        adminNotice(wp_kses_post($message));
    }
);

/**
 * @return bool
 */
function checkPluginRequirements()
{
    $min_php_version = '8.0';
    $current_php_version = phpversion();
    if (! version_compare($current_php_version, $min_php_version, '>=')) {
        adminNotice(
            sprintf(
            /* translators: %1$s is the min PHP-version, %2$s the current PHP-version */
                __(
                    'Inpsyde Google Tag Manager requires PHP version %1$1s or higher. You are running version %2$2s.',
                    'inpsyde-google-tag-manager'
                ),
                $min_php_version,
                $current_php_version
            )
        );

        return false;
    }

    if (! class_exists(GoogleTagManager::class)) {
        $autoloader = __DIR__.'/vendor/autoload.php';
        if (! file_exists($autoloader)) {
            adminNotice(
                __(
                    'Could not find a working autoloader for Inpsyde Google Tag Manager.',
                    'inpsyde-google-tag-manager'
                )
            );

            return false;
        }

        /** @noinspection PhpIncludeInspection */
        require $autoloader;
    }

    return true;
}

/**
 * @param string $message
 */
function adminNotice(string $message)
{
    $callback = function () use ($message) {
        printf(
            '<div class="notice notice-error"><p>%1$s</p></div>',
            esc_html($message)
        );
    };

    add_action('admin_notices', $callback);
    add_action('network_admin_notices', $callback);
}