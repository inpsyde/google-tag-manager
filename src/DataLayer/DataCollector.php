<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\DataLayer;

/**
 * @package Inpsyde\GoogleTagManager\DataLayer
 */
interface DataCollector
{
    public const VALUE_ENABLED = 'enable';
    public const VALUE_DISABLED = 'disable';

    public function id(): string;

    public function name(): string;

    public function description(): ?string;

    /**
     * Returns an array with all data inserted into the dataLayer.
     *
     * @return array|null
     */
    public function data(array $settings): ?array;
}
