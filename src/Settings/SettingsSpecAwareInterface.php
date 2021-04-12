<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Settings;

/**
 * Interface SettingsAwareInterface
 *
 * @package Inpsyde\GoogleTagManager\Settings
 */
interface SettingsSpecAwareInterface
{

    /**
     * Returns an array containing the fields specification and optionally validators and filters.
     *
     * @return array
     */
    public function settingsSpec(): array;
}
