<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Settings;

// phpcs:disable NeutronStandard.Functions.TypeHint

/**
 * @package Inpsyde\GoogleTagManager\Settings
 */
class SettingsRepository
{
    /**
     * SettingsRegistry constructor.
     *
     * @param string $optionName
     */
    public function __construct(protected string $optionName)
    {
    }

    /**
     * Returns the specific option for and "ID".
     *
     * @param    string $key
     *
     * @return   mixed
     */
    // phpcs:disable Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType
    public function option(string $key)
    {
        $options = $this->options();

        return isset($options[$key])
            ? $options[$key]
            : [];
    }

    /**
     * Load all options.
     *
     * @return array
     */
    public function options(): array
    {
        $options = get_option($this->optionName, []);

        return ! $options
            ? []
            : $options;
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data): bool
    {
        return update_option($this->optionName, $data);
    }
}
