<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Settings;

// phpcs:disable NeutronStandard.Functions.TypeHint

/**
 * @package Inpsyde\GoogleTagManager\Settings
 */
class SettingsRepository
{
    protected function __construct(protected string $optionName)
    {
    }

    public static function new(string $optionName): SettingsRepository
    {
        return new self($optionName);
    }

    /**
     * Returns the specific option for and "ID".
     *
     * @param string $key
     *
     * @return mixed
     */
    // phpcs:disable Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType
    public function option(string $key): mixed
    {
        $options = $this->options();

        return $options[$key] ?? [];
    }

    /**
     * Load all options.
     *
     * @return array<string, mixed>
     */
    public function options(): array
    {
        $options = get_option($this->optionName, []);

        return !$options
            ? []
            : $options;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return bool
     */
    public function update(array $data): bool
    {
        return update_option($this->optionName, $data);
    }
}
