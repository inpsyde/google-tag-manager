<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Renderer;

use Inpsyde\GoogleTagManager\App\PluginConfig;
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
    private $dataLayer;
    /**
     * @var PluginConfig
     */
    private $pluginConfig;

    /**
     * SnippetGenerator constructor.
     *
     * @param DataLayer $dataLayer
     */
    public function __construct(DataLayer $dataLayer, PluginConfig $pluginConfig)
    {
        $this->dataLayer = $dataLayer;
        $this->pluginConfig = $pluginConfig;
    }

    /**
     * Render the GTM-script tag into the head with the configured ID.
     *
     * @wp-hook wp_head
     *
     * @return bool
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

        do_action(GtmScriptTagRendererEvent::ACTION_BEFORE_SCRIPT, $this->dataLayer);

        $useNonce = apply_filters(GtmScriptTagRendererEvent::FILTER_USE_NONCE_IN_SCRIPT, false);
        $templatesDir = $this->pluginConfig->get('plugin.dir'). 'templates/';
        if ( $useNonce ) {
            $nonce = apply_filters(GtmScriptTagRendererEvent::FILTER_NONCE_IN_SCRIPT, '');
            $templateFilePath = $templatesDir . 'with-nonce-script.php';
        } else {
            $templateFilePath = $templatesDir .'regular-script.php';
        }

        // phpcs:disable
        ob_start();
        include_once $templateFilePath;
        $html = ob_get_clean();
        echo $html;
        // phpcs:enable

        do_action(GtmScriptTagRendererEvent::ACTION_AFTER_SCRIPT, $this->dataLayer);

        return true;
    }
}
