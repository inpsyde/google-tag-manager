<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Renderer;

use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Event\GtmScriptTagRendererEvent;
use Inpsyde\GoogleTagManager\Event\LogEvent;

/**
 * @package Inpsyde\GoogleTagManager\Renderer
 */
class GtmScriptTagRenderer
{
    use PrintInlineScriptTrait;

    /**
     * SnippetGenerator constructor.
     *
     * @param DataLayer $dataLayer
     */
    public function __construct(protected DataLayer $dataLayer)
    {
    }

    /**
     * Render the GTM-script tag into the head with the configured ID.
     *
     * @wp-hook wp_head
     *
     * @return bool
     *
     * phpcs:disable
     */
    public function render(): bool
    {
        $gtmId = $this->dataLayer->id();
        if ($gtmId === '') {
            do_action(
                LogEvent::ACTION,
                'error',
                'The GTM-ID is empty.',
                [
                    'method' => __METHOD__,
                    'dataLayer' => $this->dataLayer,
                ]
            );

            return false;
        }

        $dataLayerName = $this->dataLayer->name();

        /**
         * @param string $script
         * @param DataLayer $dataLayer
         *
         * @return string
         */
        $script = (string) apply_filters(
            GtmScriptTagRendererEvent::FILTER_SCRIPT,
            $this->inlineScript($dataLayerName, $gtmId),
            $this->dataLayer
        );
        /**
         * @param array $attributes
         * @param DataLayer $dataLayer
         *
         * @return array
         */
        $attributes = (array) apply_filters(
            GtmScriptTagRendererEvent::FILTER_SCRIPT_ATTRIBUTES,
            [],
            $this->dataLayer
        );

        do_action(GtmScriptTagRendererEvent::ACTION_BEFORE_SCRIPT, $this->dataLayer);
        $this->printInlineScript($script, $attributes);
        do_action(GtmScriptTagRendererEvent::ACTION_AFTER_SCRIPT, $this->dataLayer);

        return true;
    }

    /**
     * Prepares the Google Tag Manager inline script.
     *
     * @param string $dataLayerName
     * @param string $gtmId
     *
     * @return string
     */
    protected function inlineScript(string $dataLayerName, string $gtmId): string
    {
        ob_start();
        ?>
        (
        function( w, d, s, l, i ) {
            w[l] = w[l] || [];
            w[l].push( {'gtm.start': new Date().getTime(), event: 'gtm.js'} );
					var f = d.getElementsByTagName( s )[0],
						j = d.createElement( s ), dl = l !== 'dataLayer' ? '&l=' + l : '';
					j.async = true;
					j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
					f.parentNode.insertBefore( j, f );
				}
        )( window, document, 'script', '<?= esc_js($dataLayerName); ?>', '<?= esc_js($gtmId); ?>' );
        <?php

        return ob_get_clean();
    }

}
