<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Event;

/**
 * @package Inpsyde\GoogleTagManager\Event
 */
final class GtmScriptTagRendererEvent
{
    // Actions for "before" and "after" <script>.
    public const ACTION_AFTER_SCRIPT = 'inpsyde-google-tag-manager.after-script';
    public const ACTION_BEFORE_SCRIPT = 'inpsyde-google-tag-manager.before-script';
    // Filters
    public const FILTER_SCRIPT = 'inpsyde-google-tag-manager.filter.script';
    public const FILTER_SCRIPT_ATTRIBUTES = 'inpsyde-google-tag-manager.filter.gtm-script-attributes';
}
