<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Renderer;

use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;

/**
 * @package Inpsyde\GoogleTagManager\Renderer
 */
class DataLayerRenderer {

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
	 * Rendering the dataLayer values defined in Backend.
	 *
	 * @return bool
	 */
	public function render(): bool {

		$data            = $this->data_layer->data();
		$data_layer_name = $this->data_layer->name();

		$data_layer_js = array_reduce(
			$data,
			function ( $js, DataCollectorInterface $data ) use ( $data_layer_name ) {

				$js .= "\n";
				$js .= sprintf(
					'%1$s.push(%2$s);',
					esc_js( $data_layer_name ),
					wp_json_encode( $data->data() )
				);

				return $js;
			},
			''
		);
		?>
		<script>
			<?php
			printf(
				'var %1$s = %1$s || [];',
				esc_js( $data_layer_name )
			);
			echo $data_layer_js; /* xss ok */
			?>
		</script>
		<?php

		return TRUE;
	}

}
