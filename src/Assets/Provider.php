<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Assets;

use Inpsyde\GoogleTagManager\Core\BootableProviderInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * @package Inpsyde\GoogleTagManager\Assets
 */
final class Provider implements ServiceProviderInterface, BootableProviderInterface {

	/**
	 * @param Container $plugin
	 */
	public function register( Container $plugin ) {

		$plugin[ 'Assets.SettingsPage' ] = function ( $plugin ): SettingsPage {

			return new SettingsPage( $plugin[ 'config' ] );
		};

	}

	/**
	 * @param Container $plugin
	 */
	public function boot( Container $plugin ) {

		if ( is_admin() ) {

			add_action(
				'admin_enqueue_scripts',
				[ $plugin[ 'Assets.SettingsPage' ], 'register_scripts' ]
			);

			add_action(
				'admin_enqueue_scripts',
				[ $plugin[ 'Assets.SettingsPage' ], 'register_styles' ]
			);

		}
	}

}
