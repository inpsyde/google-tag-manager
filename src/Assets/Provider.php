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
	 * {@inheritdoc}
	 */
	public function register( Container $plugin ) {

		$plugin[ 'Assets.SettingsPage' ] = function ( $plugin ): SettingsPage {

			return new SettingsPage( $plugin[ 'config' ] );
		};

	}

	/**
	 * {@inheritdoc}
	 */
	public function boot( Container $plugin ) {

		if ( is_admin() ) {

			add_filter(
				'admin_head',
				[ $plugin[ 'Assets.SettingsPage' ], 'register_scripts' ]
			);

			add_filter(
				'admin_head',
				[ $plugin[ 'Assets.SettingsPage' ], 'register_styles' ]
			);

		}
	}

}