<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Renderer;

use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\GoogleTagManager;

/**
 * @package Inpsyde\GoogleTagManager\Renderer
 */
class GtmScriptTagRenderer {

	const ACTION_AFTER_SCRIPT = 'inpsyde-google-tag-manager.after-script';
	const ACTION_BEFORE_SCRIPT = 'inpsyde-google-tag-manager.before-script';

	/**
	 * @var DataLayer
	 */
	private $data_layer;

	/**
	 * SnippetGenerator constructor.
	 *
	 * @param DataLayer $data_layer
	 */
	public function __construct( DataLayer $data_layer ) {

		$this->data_layer = $data_layer;
	}

	/**
	 * Render the GTM-script tag into the head with the configured ID.
	 *
	 * @wp-hook wp_head
	 */
	public function render() {

		$gtm_id = $this->data_layer->id();
		if ( $gtm_id === '' ) {

			do_action(
				GoogleTagManager::ACTION_ERROR,
				'The GTM-ID is empty.',
				[
					'method'    => __METHOD__,
					'dataLayer' => $this->data_layer,
				]
			);

			return FALSE;
		}

		$data_layer_name = $this->data_layer->name();

		do_action( self::ACTION_BEFORE_SCRIPT, $this->data_layer );
		?>
		<script>(
				function( w, d, s, l, i ) {
					w[ l ] = w[ l ] || [];
					w[ l ].push( {
						'gtm.start': new Date().getTime(), event: 'gtm.js'
					} );
					var f = d.getElementsByTagName( s )[ 0 ],
						j = d.createElement( s ), dl = l !== 'dataLayer' ? '&l=' + l : '';
					j.async = true;
					j.src =
						'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
					f.parentNode.insertBefore( j, f );
				}
			)( window, document, 'script', '<?= esc_js( $data_layer_name ); ?>', '<?= esc_js( $gtm_id ); ?>' );
		</script>
		<?php
		do_action( self::ACTION_AFTER_SCRIPT, $this->data_layer );

		return TRUE;
	}

}
