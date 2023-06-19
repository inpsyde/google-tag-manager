<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Renderer;

use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Event\LogEvent;

/**
 * @package Inpsyde\GoogleTagManager\Renderer
 */
class NoscriptTagRenderer
{
    public const GTM_URL = 'https://www.googletagmanager.com/ns.html';

    /**
     * SnippetGenerator constructor.
     *
     * @param DataLayer $dataLayer
     */
    public function __construct(protected DataLayer $dataLayer)
    {
    }

    /**
     * @wp-hook wp_body_open
     */
    public function renderAtBodyStart()
    {
        if (!$this->dataLayer->autoInsertNoscript()) {
            return;
        }
        $this->render();
    }

    /**
     * Rendering the <noscript>-tag for Google Tag Manager.
     *
     * @wp-hook inpsyde-google-tag-manager.noscript
     */
    // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
    public function render()
    {
        echo $this->noscript();
    }
    // phpcs:enable

    /**
     * Returns the <noscript>-tag for GTM.
     *
     * @link https://developers.google.com/tag-manager/devguide#adding-data-layer-variables-for-devices-without-javascript-support
     *
     * @return string
     */
    private function noscript(): string
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

            return '';
        }

        $url = add_query_arg(
            [
                'id' => $gtmId,
            ],
            self::GTM_URL
        );

        // adding the data to the iframe src as query param.
        $url = array_reduce(
            $this->dataLayer->data(),
            static function (string $url, DataCollectorInterface $data): string {
                return add_query_arg($data->data(), $url);
            },
            $url
        );

        $iframe = sprintf(
            '<iframe src="%s" height="0" width="0" style="%s"></iframe>',
            \esc_url($url),
            'display:none;visibility:hidden'
        );

        return '<noscript>' . $iframe . '</noscript>';
    }
}
