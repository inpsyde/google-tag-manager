<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-
/**
 * Plugin Name: Inpsyde Google Tag Manager
 * Description: Adds the Google Tag Manager container snippet to your site and populates the Google Tag Manager Data Layer.
 * Plugin URI:  https://inpsyde.com
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

		if ( ! class_exists( GoogleTagManager::class ) ) {
			$autoloader = __DIR__ . '/vendor/autoload.php';
			if ( file_exists( $autoloader ) ) {
				/** @noinspection PhpIncludeInspection */
				require $autoloader;
			} else {

				add_action(
					'admin_notices',
					function () {

						$message = __(
							'Could not find a working autoloader for Inpsyde Google Tag Manager.',
							'inpsyde-google-tag-manager'
						);

						printf(
							'<div class="notice notice-error"><p>%1$s</p></div>',
							esc_html( $message )
						);
					}
				);

				return;
			}
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
