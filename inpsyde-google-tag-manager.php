<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-
/**
 * Plugin Name: Inpsyde Google Tag Manager
 * Description: Adding Google Tag Manager with custom data to your site.
 * Plugin URI:  http://inpsyde.com
 * Version:     0.1.0
 * Author:      Inpsyde GmbH, cb
 * Author URI:  https://inpsyde.com
 * Licence:     MIT
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

		if ( file_exists( __DIR__ . '/vendor/autoload.php' ) && ! class_exists( GoogleTagManager::class ) ) {
			/** @noinspection PhpIncludeInspection */
			require_once __DIR__ . '/vendor/autoload.php';
		}

		$config = ConfigBuilder::plugin_from_file( __FILE__ );

		load_plugin_textdomain( $config->get( 'plugin.textdomain' ) );

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

	} catch ( \Throwable $e ) {

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			throw $e;
		}

		do_action( GoogleTagManager::ACTION_ERROR, $e );
	}

}
