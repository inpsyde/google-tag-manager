<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Settings;

use Inpsyde\GoogleTagManager\Core\BootableProviderInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * @package Inpsyde\GoogleTagManager\Settings
 */
final class Provider implements ServiceProviderInterface, BootableProviderInterface {

	/**
	 * @param Container $plugin
	 */
	public function register( Container $plugin ) {

		$plugin[ 'Settings.SettingsRepository' ] = function ( Container $plugin ): SettingsRepository {

			return new SettingsRepository( $plugin[ 'config' ]->get( 'plugin.textdomain' ) );
		};

		$plugin[ 'Settings.Page' ] = function ( Container $plugin ): SettingsPage {

			return new SettingsPage(
				new View\TabbedSettingsPageView( $plugin[ 'config' ] ),
				$plugin[ 'Settings.SettingsRepository' ]
			);
		};
	}

	/**
	 * @param Container $plugin
	 */
	public function boot( Container $plugin ) {

		if ( is_admin() ) {

			add_action(
				'admin_menu',
				[ $plugin[ 'Settings.Page' ], 'register' ]
			);

		}
	}

}
