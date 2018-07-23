<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Renderer;

use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;

/**
 * @package Inpsyde\GoogleTagManager\Renderer
 */
class DataLayerRenderer
{

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

        $dataLayerJs = array_reduce(
            $data,
            function (string $script, DataCollectorInterface $data) use ($dataLayerName): string {
                $script .= "\n";
                $script .= sprintf('%1$s.push(%2$s);', esc_js($dataLayerName), wp_json_encode($data->data()));

                return $script;
            },
            ''
        );
        ?>
        <script>
            <?php
            printf('var %1$s = %1$s || [];', esc_js($dataLayerName));
            echo $dataLayerJs; /* xss ok */
            ?>
        </script>
        <?php

        return true;
    }
}
