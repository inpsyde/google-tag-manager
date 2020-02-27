<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: Inpsyde Google Tag Manager
 * Description: Adds the Google Tag Manager container snippet to your site and populates the Google Tag Manager Data Layer.
 * Plugin URI:  https://wordpress.org/plugins/inpsyde-google-tag-manager
 * Version:     1.3
 * Author:      Inpsyde GmbH 
 * Author URI:  https://inpsyde.com
 * Licence:     GPLv3
 * Text Domain: inpsyde-google-tag-manager
 */

namespace Inpsyde\GoogleTagManager;

use Inpsyde\GoogleTagManager\App\ConfigBuilder;
use Inpsyde\GoogleTagManager\Event\LogEvent;

if (! function_exists('add_filter')) {
    return;
}

add_action('plugins_loaded', __NAMESPACE__.'\initialize');

/**
 * @wp-hook plugins_loaded
 *
 * @throws \Throwable   When WP_DEBUG=TRUE exceptions will be thrown.
 */
function initialize()
{
    try {
        load_plugin_textdomain('inpsyde-google-tag-manager');

        if (! checkPluginRequirements()) {
            return false;
        }

        (new GoogleTagManager())
            ->set('config', ConfigBuilder::fromFile(__FILE__)->freeze())
            ->register(new App\Provider\AssetProvider())
            ->register(new App\Provider\FormProvider())
            ->register(new App\Provider\DataLayerProvider())
            ->register(new App\Provider\RendererProvider())
            ->register(new App\Provider\SettingsProvider())
            ->boot();
    } catch (\Throwable $exception) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            throw $exception;
        }

        do_action(LogEvent::ACTION, 'critical', $exception);

        return false;
    }

    return true;
}

/**
 * @return bool
 */
function checkPluginRequirements()
{
    $min_php_version = '7.0';
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
    add_action(
        'admin_notices',
        function () use ($message) {
            printf(
                '<div class="notice notice-error"><p>%1$s</p></div>',
                esc_html($message)
            );
        }
    );
}
