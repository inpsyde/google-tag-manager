<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Renderer;

use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Event\GtmScriptTagRendererEvent;
use Inpsyde\GoogleTagManager\Event\LogEvent;

/**
 * @package Inpsyde\GoogleTagManager\Renderer
 */
class GtmScriptTagRenderer
{

    /**
     * @var DataLayer
     */
    private $data_layer;

    /**
     * SnippetGenerator constructor.
     *
     * @param DataLayer $data_layer
     */
    public function __construct(DataLayer $data_layer)
    {

        $this->data_layer = $data_layer;
    }

    /**
     * Render the GTM-script tag into the head with the configured ID.
     *
     * @wp-hook wp_head
     *
     * @return bool
     */
    public function render() : bool
    {

        $gtm_id = $this->data_layer->id();
        if ($gtm_id === '') {
            do_action(
                LogEvent::ACTION,
                'error',
                'The GTM-ID is empty.',
                [
                    'method'    => __METHOD__,
                    'dataLayer' => $this->data_layer,
                ]
            );

            return false;
        }

        $data_layer_name = $this->data_layer->name();

        do_action(GtmScriptTagRendererEvent::ACTION_BEFORE_SCRIPT, $this->data_layer);

        // phpcs:disable
        ?>
        <script>
			(function ( w, d, s, l, i ) {
				w[l] = w[l] || [];
				w[l].push( {'gtm.start': new Date().getTime(), event: 'gtm.js'} );
				var f = d.getElementsByTagName( s )[0],
					j = d.createElement( s ), dl = l !== 'dataLayer' ? '&l=' + l : '';
				j.async = true;
				j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
				f.parentNode.insertBefore( j, f );
			})( window, document, 'script', '<?= esc_js($data_layer_name); ?>', '<?= esc_js($gtm_id); ?>' );
        </script>
        <?php
        // phpcs:enable

        do_action(GtmScriptTagRendererEvent::ACTION_AFTER_SCRIPT, $this->data_layer);

        return true;
    }
}
