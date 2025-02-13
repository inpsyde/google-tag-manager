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
     * @var DataLayer
     */
    private $dataLayer;

    /**
     * SnippetGenerator constructor.
     *
     * @param DataLayer $dataLayer
     */
    public function __construct(DataLayer $dataLayer)
    {
        $this->dataLayer = $dataLayer;
    }

    /**
     * Rendering the dataLayer values defined in Backend.
     *
     * @return bool
     */
    public function render(): bool
    {
        $data = $this->dataLayer->data();
        $dataLayerName = $this->dataLayer->name();

        $dataLayerJs = sprintf('var %1$s = %1$s || [];', esc_js($dataLayerName));
        $dataLayerJs = array_reduce(
            $data,
            static function (string $script, DataCollectorInterface $data) use ($dataLayerName): string {
                $decodedData = array_map(
                    static function ($item) {
                        return is_array($item)
                            ? array_map('html_entity_decode', $item)
                            : html_entity_decode($item);
                    },
                    $data->data()
                );

                $script .= "\n";
                $script .= sprintf(
                    '%1$s.push(%2$s);',
                    esc_js($dataLayerName),
                    (string) wp_json_encode($decodedData)
                );

                return $script;
            },
            $dataLayerJs
        );

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
