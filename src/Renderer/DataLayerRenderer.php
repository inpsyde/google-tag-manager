<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Renderer;

use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Event\DataLayerRendererEvent;

/**
 * @package Inpsyde\GoogleTagManager\Renderer
 */
class DataLayerRenderer
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
     * Rendering the dataLayer values defined in Backend.
     *
     * @return bool
     */
    public function render(): bool
    {
        $dataCollectors = $this->dataLayer->data();
        $dataLayerName = $this->dataLayer->name();

        $dataLayerJs = sprintf('var %1$s = %1$s || [];', esc_js($dataLayerName));

        foreach ($dataCollectors as $collector) {
            $data = $collector->data();
            if (!is_array($data) || count($data) < 1) {
                continue;
            }
            $dataLayerJs .= "\n";
            $dataLayerJs .= sprintf(
                '%1$s.push(%2$s);',
                esc_js($dataLayerName),
                wp_json_encode($data)
            );
        }

        /**
         * @param array $attributes
         * @param DataLayer $dataLayer
         *
         * @return array $attributes
         */
        $attributes = (array) apply_filters(
            DataLayerRendererEvent::FILTER_SCRIPT_ATTRIBUTES,
            [],
            $this->dataLayer
        );

        $this->printInlineScript($dataLayerJs, $attributes);

        return true;
    }
    // phpcs:enable
}
