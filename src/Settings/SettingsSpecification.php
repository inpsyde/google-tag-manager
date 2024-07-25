<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Settings;

/**
 * @package Inpsyde\GoogleTagManager\Settings
 */
interface SettingsSpecification
{
    /**
     * Returns an array containing the field specification
     * used to render the settings page.
     *
     * @return array
     */
    public function specification(): array;

    /**
     * Sanitize data before validation.
     *
     * @param array $data
     *
     * @return array
     */
    public function sanitize(array $data): array;

    /**
     * @param array $data
     *
     * @return ?\WP_Error
     */
    public function validate(array $data): ?\WP_Error;
}
