<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Renderer;

use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Event\LogEvent;

/**
 * @package Inpsyde\GoogleTagManager\Renderer
 */
class NoscriptTagRenderer
{
    public const GTM_URL = 'https://www.googletagmanager.com/ns.html';

    protected function __construct(protected DataLayer $dataLayer)
    {
    }

    public static function new(DataLayer $dataLayer): self
    {
        return new self($dataLayer);
    }

    /**
     * @wp-hook wp_body_open
     */
    public function renderAtBodyStart(): void
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
    public function render(): void
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
        $gtmId = $this->dataLayer->gtmId();
        if ($gtmId === '') {
            do_action(
                LogEvent::ACTION,
                'error',
                'The GTM-ID is empty.',
                [
                    'method' => __METHOD__,
                    'dataLayer' => $this->dataLayer,
                ],
            );

            return '';
        }

        $queryArgs = ['id' => $gtmId];

        foreach ($this->dataLayer->data() as $data) {
            $queryArgs[] = $data;
        }

        $url = add_query_arg(
            $queryArgs,
            self::GTM_URL,
        );

        $iframe = sprintf(
            '<iframe src="%1$s" height="0" width="0" style="%2$s"></iframe>',
            \esc_url($url),
            'display:none;visibility:hidden',
        );

        return '<noscript>' . $iframe . '</noscript>';
    }
}
