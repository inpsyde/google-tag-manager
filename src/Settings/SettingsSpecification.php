<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Settings;

/**
 * @package Inpsyde\GoogleTagManager\Settings
 * @phpstan-type SelectOption array{
 *     label: string,
 *     value: string,
 *     id?: string,
 *     disabled?: bool,
 * }
 * @phpstan-type Specification array{
 *     label: string,
 *     name: string,
 *     description?: string,
 *     type: 'text' | 'select' | 'checkbox',
 *     choices?: SelectOption[],
 * }
 */
interface SettingsSpecification
{
    /**
     * Returns an array containing the field specification
     * used to render the settings page.
     *
     * @return Specification[]
     */
    public function specification(): array;

    /**
     * Sanitize data before validation.
     *
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function sanitize(array $data): array;

    /**
     * @param array<string, mixed> $data
     *
     * @return null|\WP_Error
     */
    public function validate(array $data): ?\WP_Error;
}
