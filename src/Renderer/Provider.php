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
	 * {@inheritdoc}
	 */
	public function register( Container $plugin ) {

		$plugin[ 'Renderer.SnippetGenerator' ] = function ( Container $plugin ): SnippetGenerator {

			return new SnippetGenerator( $plugin[ 'DataLayer' ] );
		};
	}

	/**
	 * {@inheritdoc}
	 */
	public function boot( Container $plugin ) {

		if ( is_admin() ) {
			return;
		}

		add_action(
			'wp_head',
			[ $plugin[ 'Renderer.SnippetGenerator' ], 'render_data_layer' ]
		);

		add_action(
			'wp_head',
			[ $plugin[ 'Renderer.SnippetGenerator' ], 'render_gtm_script' ]
		);

		add_action(
			'inpsyde-google-tag-manager.noscript',
			[ $plugin[ 'Renderer.SnippetGenerator' ], 'render_noscript' ]
		);

		add_action(
			'body_class',
			[ $plugin[ 'Renderer.SnippetGenerator' ], 'render_noscript_at_body_start' ],
			PHP_INT_MAX
		);

	}

}