<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Event;

/**
 * @package Inpsyde\GoogleTagManager\Event
 */
final class GtmScriptTagRendererEvent
{

    const ACTION_AFTER_SCRIPT = 'inpsyde-google-tag-manager.after-script';
    const ACTION_BEFORE_SCRIPT = 'inpsyde-google-tag-manager.before-script';
    const FILTER_USE_NONCE_IN_SCRIPT = 'inpsyde-google-tag-manager.use-nonce-in-script';
    const FILTER_NONCE_IN_SCRIPT = 'inpsyde-google-tag-manager.nonce-in-script';
}
