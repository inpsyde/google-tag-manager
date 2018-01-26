<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Renderer;

use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Event\LogEvent;

/**
 * @package Inpsyde\GoogleTagManager\Renderer
 */
class NoscriptTagRenderer
{

    const GTM_URL = 'https://www.googletagmanager.com/ns.html';

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
     * Rendering the <noscript>-tag for Google Tag Manager.
     *
     * @wp-hook inpsyde-google-tag-manager.noscript
     */
    public function render()
    {

        echo $this->noscript(); /* xss ok */
    }

    /**
     * Returns the <noscript>-tag for GTM.
     *
     * @link https://developers.google.com/tag-manager/devguide#adding-data-layer-variables-for-devices-without-javascript-support
     *
     * @return string
     */
    private function noscript(): string
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

            return '';
        }

        $url = add_query_arg(
            [
                'id' => $gtm_id,
            ],
            self::GTM_URL
        );

        // adding the data to the iframe src as query param.
        $url = array_reduce(
            $this->data_layer->data(),
            function (string $url, DataCollectorInterface $data): string {

                return add_query_arg($data->data(), $url);
            },
            $url
        );

        $iframe = sprintf(
            '<iframe src="%s" height="0" width="0" style="display:none;visibility:hidden"></iframe>',
            $url
        );

        return '<noscript>' . $iframe . '</noscript>';
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
    public function renderAtBodyStart(array $classes = []): array
    {

        if (!$this->data_layer->autoInsertNoscript()) {
            return $classes;
        }

        $html = $this->noscript();
        if ($html === '') {
            return $classes;
        }

        $classes[] = '">' . $html . '<br style="display:none;';

        return $classes;
    }
}
