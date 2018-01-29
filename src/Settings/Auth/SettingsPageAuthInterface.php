<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Settings\Auth;

use Brain\Nonces\NonceInterface;

/**
 * Interface SettingsPageAuthInterface
 *
 * @package Inpsyde\GoogleTagManager\Settings
 */
interface SettingsPageAuthInterface
{

    /**
     * @param array $request_data
     *
     * @return bool
     */
    public function isAllowed(array $request_data = []): bool;

    /**
     * @return NonceInterface
     */
    public function nonce(): NonceInterface;

    /**
     * @return string
     */
    public function cap(): string;
}
