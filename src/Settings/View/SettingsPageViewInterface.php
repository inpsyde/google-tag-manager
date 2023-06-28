<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Settings\View;

use Brain\Nonces\NonceInterface;
use ChriCo\Fields\Element\FormInterface;

/**
 * @package Inpsyde\GoogleTagManager\Settings
 */
interface SettingsPageViewInterface
{

    /**
     * The name of the page for nav menu.
     *
     * @return string
     */
    public function name(): string;

    /**
     * The menu-slug.
     *
     * @return string
     */
    public function slug(): string;

    /**
     * @param FormInterface $form
     * @param NonceInterface $nonce
     */
    public function render(FormInterface $form, NonceInterface $nonce);
}
