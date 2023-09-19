<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Settings\Auth;

use Brain\Nonces\ArrayContext;
use Brain\Nonces\NonceInterface;
use Brain\Nonces\WpNonce;
use Inpsyde\GoogleTagManager\Event\LogEvent;

/**
 * @package Inpsyde\GoogleTagManager\Settings
 */
class SettingsPageAuth implements SettingsPageAuthInterface
{

    public const DEFAULT_CAP = 'manage_options';

    private string $cap;

    private NonceInterface $nonce;

    /**
     * SettingsPageAuth constructor.
     *
     * @param string $action
     * @param string $cap
     * @param NonceInterface $nonce
     */
    public function __construct(string $action, string $cap = null, NonceInterface $nonce = null)
    {
        $this->cap = $cap ?? self::DEFAULT_CAP;
        $this->nonce = $nonce ?? new WpNonce($action.'_nonce');
    }

    /**
     * @param array $requestData
     *
     * @return bool
     */
    public function isAllowed(array $requestData = []): bool
    {
        if (! current_user_can($this->cap)) {
            do_action(
                LogEvent::ACTION,
                'error',
                'User has no sufficient rights to save page',
                [
                    'method' => __METHOD__,
                    'cap' => $this->cap,
                    'nonce' => $this->nonce,
                ]
            );

            return false;
        }

        if (is_multisite() && ms_is_switched()) {
            return false;
        }

        return $this->nonce->validate(new ArrayContext($requestData));
    }

    /**
     * @return NonceInterface
     */
    public function nonce(): NonceInterface
    {
        return $this->nonce;
    }

    /**
     * @return string
     */
    public function cap(): string
    {
        return $this->cap;
    }
}
