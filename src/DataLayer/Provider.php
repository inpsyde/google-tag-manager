<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\DataLayer;

use Inpsyde\GoogleTagManager\Core\BootableProviderInterface;
use Inpsyde\GoogleTagManager\DataLayer\Site\SiteInfoDataCollector;
use Inpsyde\GoogleTagManager\DataLayer\User\UserDataCollector;
use Inpsyde\GoogleTagManager\Settings\SettingsPage;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer
 */
final class Provider implements ServiceProviderInterface, BootableProviderInterface {

	/**
	 * @param Container $plugin
	 */
	public function register( Container $plugin ) {

		$plugin[ 'DataLayer' ] = function ( Container $plugin ): DataLayer {

			return new DataLayer( $plugin[ 'Settings.SettingsRepository' ] );
		};

		$plugin[ 'DataLayer.User.UserDataCollector' ] = function ( Container $plugin ): UserDataCollector {

			return new UserDataCollector( $plugin[ 'Settings.SettingsRepository' ] );
		};

		$plugin[ 'DataLayer.Site.SiteInfoDataCollector' ] = function ( Container $plugin ): SiteInfoDataCollector {

			return new SiteInfoDataCollector( $plugin[ 'Settings.SettingsRepository' ] );
		};

	}

	/**
	 * @param Container $plugin
	 */
	public function boot( Container $plugin ) {

		$plugin->extend(
			'DataLayer',
			function ( DataLayer $data_layer, Container $plugin ) {

				$data_layer->add_data( $plugin[ 'DataLayer.User.UserDataCollector' ] );
				$data_layer->add_data( $plugin[ 'DataLayer.Site.SiteInfoDataCollector' ] );

				return $data_layer;
			}
		);

		if ( is_admin() ) {

			$plugin->extend(
				'Settings.Page',
				function ( SettingsPage $page, Container $plugin ): SettingsPage {

					$factory  = $plugin[ 'ChriCo.Fields.ElementFactory' ];
					$settings = [
						$plugin[ 'DataLayer' ]->settings_spec(),
						$plugin[ 'DataLayer.User.UserDataCollector' ]->settings_spec(),
						$plugin[ 'DataLayer.Site.SiteInfoDataCollector' ]->settings_spec(),
					];
					foreach ( $settings as $spec ) {
						$page->add_element(
							$factory->create( $spec ),
							$spec[ 'filters' ] ?? [],
							$spec[ 'validators' ] ?? []
						);
					}

					return $page;
				}
			);
		}
	}
}
