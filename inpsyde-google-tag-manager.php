<?php # -*- coding: utf-8 -*-

/**
 * Plugin Name: Inpsyde Google Tag Manager
 * Description: Adds the Google Tag Manager container snippet to your site and populates the Google Tag Manager Data Layer.
 * Plugin URI:  https://wordpress.org/plugins/inpsyde-google-tag-manager
 * Version:     1.0.0
 * Author:      Inpsyde GmbH
 * Author URI:  https://inpsyde.com
 * Licence:     GPLv3
 * Text Domain: inpsyde-google-tag-manager
 */

namespace Inpsyde\GoogleTagManager;

use ChriCo\Fields\Extension\Pimple\Provider as FormProvider;
use Inpsyde\GoogleTagManager\Core\ConfigBuilder;

if ( ! function_exists( 'add_filter' ) ) {
	return;
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\initialize' );

/**
 * @wp-hook plugins_loaded
 *
 * @throws \Throwable   When WP_DEBUG=TRUE exceptions will be thrown.
 */
function initialize() {

	try {

		load_plugin_textdomain( 'inpsyde-google-tag-manager' );

		if ( ! check_plugin_requirements() ) {

			return FALSE;
		}

		$config = ConfigBuilder::from_file( __FILE__ );

		$plugin = new GoogleTagManager(
			[
				'config' => $config->freeze(),
			]
		);

		$plugin->register( new Assets\Provider() );
		$plugin->register( new FormProvider() );
		$plugin->register( new DataLayer\Provider() );
		$plugin->register( new Settings\Provider() );
		$plugin->register( new Renderer\Provider() );

		$plugin->boot();

		return TRUE;
	} catch ( \Throwable $e ) {

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			throw $e;
		}

		do_action( GoogleTagManager::ACTION_ERROR, $e );

		return FALSE;
	}

}

/**
 * @return bool
 */
function check_plugin_requirements() {

	$min_php_version = '7.0';
	$current_php_version = phpversion();
	if ( ! version_compare( $current_php_version, $min_php_version, '>=' ) ) {
		admin_notice(
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

		return FALSE;
	}

	if ( ! class_exists( GoogleTagManager::class ) ) {
		$autoloader = __DIR__ . '/vendor/autoload.php';
		if ( file_exists( $autoloader ) ) {
			/** @noinspection PhpIncludeInspection */
			require $autoloader;
		} else {

			admin_notice(
				__(
					'Could not find a working autoloader for Inpsyde Google Tag Manager.',
					'inpsyde-google-tag-manager'
				)
			);

			return FALSE;
		}
	}

	return TRUE;
}

/**
 * @param string $message
 */
function admin_notice( string $message ) {

	add_action(
		'admin_notices',
		function () use ( $message ) {

			printf(
				'<div class="notice notice-error"><p>%1$s</p></div>',
				esc_html( $message )
			);
		}
	);

}
