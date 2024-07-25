<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Renderer;

use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Event\DataLayerRendererEvent;

/**
 * @package Inpsyde\GoogleTagManager\Renderer
 */
class DataLayerRenderer
{
    use PrintInlineScriptTrait;

    protected function __construct(protected DataLayer $dataLayer)
    {
    }

    public static function new(DataLayer $dataLayer): self
    {
        return new self($dataLayer);
    }

    /**
     * Rendering the dataLayer values defined in Backend.
     *
     * @return bool
     */
    public function render(): bool
    {
        $dataLayerPushData = $this->dataLayer->data();
        $dataLayerName = $this->dataLayer->dataLayerName();

        $dataLayerJs = sprintf('var %1$s = %1$s || [];', esc_js($dataLayerName));

        foreach ($dataLayerPushData as $data) {
            /** @psalm-suppress DocblockTypeContradiction */
            if (!is_array($data) || count($data) < 1) {
                continue;
            }
            $dataLayerJs .= "\n";
            $dataLayerJs .= sprintf(
                '%1$s.push(%2$s);',
                esc_js($dataLayerName),
                (string) wp_json_encode($data)
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
