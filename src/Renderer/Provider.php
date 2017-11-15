<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Renderer;

use Inpsyde\GoogleTagManager\Core\BootableProviderInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * @package Inpsyde\GoogleTagManager\Renderer
 */
final class Provider implements ServiceProviderInterface, BootableProviderInterface {

	/**
	 * @param Container $plugin
	 */
	public function register( Container $plugin ) {

		$plugin[ 'Renderer.GtmScriptTagRenderer' ] = function ( Container $plugin ): GtmScriptTagRenderer {

			return new GtmScriptTagRenderer( $plugin[ 'DataLayer' ] );
		};

		$plugin[ 'Renderer.DataLayerRenderer' ] = function ( Container $plugin ): DataLayerRenderer {

			return new DataLayerRenderer( $plugin[ 'DataLayer' ] );
		};

		$plugin[ 'Renderer.NoscriptTagRenderer' ] = function ( Container $plugin ): NoscriptTagRenderer {

			return new NoscriptTagRenderer( $plugin[ 'DataLayer' ] );
		};
	}

	/**
	 * @param Container $plugin
	 */
	public function boot( Container $plugin ) {

		if ( ! is_admin() ) {

			add_action(
				GtmScriptTagRenderer::ACTION_BEFORE_SCRIPT,
				[ $plugin[ 'Renderer.DataLayerRenderer' ], 'render' ]
			);

			add_action(
				'wp_head',
				[ $plugin[ 'Renderer.GtmScriptTagRenderer' ], 'render' ]
			);

			add_action(
				NoscriptTagRenderer::ACTION_RENDER_NOSCRIPT,
				[ $plugin[ 'Renderer.NoscriptTagRenderer' ], 'render' ]
			);

			add_action(
				'body_class',
				[ $plugin[ 'Renderer.NoscriptTagRenderer' ], 'render_at_body_start' ],
				PHP_INT_MAX
			);

		}
	}

}
