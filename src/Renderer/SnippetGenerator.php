<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Renderer;

use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;

/**
 * @package Inpsyde\GoogleTagManager\Renderer
 */
class SnippetGenerator {

	const GTM_NOSCRIPT_URL = 'https://www.googletagmanager.com/ns.html';

	/**
	 * @var DataLayer
	 */
	private $dataLayer;

	/**
	 * SnippetGenerator constructor.
	 *
	 * @param DataLayer $dataLayer
	 */
	public function __construct( DataLayer $dataLayer ) {

		$this->dataLayer = $dataLayer;
	}

	/**
	 * Rendering the dataLayer values defined in Backend.
	 *
	 * @return bool
	 */
	public function render_data_layer(): bool {

		$data            = $this->dataLayer->data();
		$data_layer_name = esc_js( $this->dataLayer->name() );
		?>
		<script><?php
			printf(
				'var %1$s = %1$s || [];',
				$data_layer_name
			);
			echo array_reduce(
				$data,
				function ( $html, DataCollectorInterface $data ) use ( $data_layer_name ) {

					$html .= "\n";
					$html .= sprintf(
						'%1$s.push(%2$s);',
						$data_layer_name,
						json_encode( $data->data() )
					);

					return $html;
				},
				""
			);
			?></script>
		<?php

		return TRUE;
	}

	/**
	 * Render the GTM-script tag into the head with the configured ID.
	 *
	 * @wp-hook wp_head
	 */
	public function render_gtm_script() {

		$gtm_id = $this->dataLayer->id();
		if ( $gtm_id === '' ) {

			return FALSE;
		}

		$data_layer_name = $this->dataLayer->name();
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
			)( window, document, 'script', '<?= esc_js( $data_layer_name ); ?>', '<?= esc_js( $gtm_id ); ?>' );</script>
		<?php

		return TRUE;
	}

	/**
	 * Rendering the <noscript>-tag for Google Tag Manager.
	 *
	 * @wp-hook inpsyde-google-tag-manager.noscript
	 */
	public function render_noscript() {

		echo $this->get_noscript();
	}

	/**
	 * Returns the <noscript>-tag for GTM.
	 *
	 * @link https://developers.google.com/tag-manager/devguide#adding-data-layer-variables-for-devices-without-javascript-support
	 *
	 * @return string
	 */
	private function get_noscript(): string {

		$gtm_id = $this->dataLayer->id();
		if ( $gtm_id === '' ) {

			return '';
		}

		$url = add_query_arg( [ 'id' => $gtm_id ], self::GTM_NOSCRIPT_URL );

		// adding the data to the iframe src as query param.
		$url = array_reduce(
			$this->dataLayer->data(),
			function ( $url, DataCollectorInterface $data ): string {

				return add_query_arg( $data->data(), $url );
			},
			$url
		);

		return sprintf(
			'<noscript><iframe src="%s" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>',
			$url
		);
	}

	/**
	 * Trying to render the <noscript> for GTM after the <body>-tag by hacking into body_class.
	 *
	 * @wp-hook body_class
	 *
	 * @param array $classes
	 *
	 * @return array $classes
	 */
	public function render_noscript_at_body_start( array $classes = [] ): array {

		if ( ! $this->dataLayer->auto_insert_noscript() ) {

			return $classes;
		}

		$html = $this->get_noscript();
		if ( $html === '' ) {

			return $classes;
		}

		$classes[] = '">' . $html . '<br style="display:none;';

		return $classes;
	}

}