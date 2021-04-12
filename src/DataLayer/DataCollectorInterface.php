<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\DataLayer;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer
 */
interface DataCollectorInterface
{

    public const VALUE_ENABLED = 'enabled';
    public const VALUE_DISABLED = 'disabled';

    /**
     * Checks if for the current page this is usable.
     *
     * @return bool
     */
    public function isAllowed(): bool;

    /**
     * Returns an array with all data inserted into the dataLayer.
     *
     * @return array
     */
    public function data(): array;
}
