<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\App;

use Inpsyde\GoogleTagManager\GoogleTagManager;

/**
 * Interface BootableServiceProvider
 *
 * @package Inpsyde\GoogleTagManager\App
 */
interface BootableProvider extends Provider
{

    /**
     * @param GoogleTagManager $plugin
     */
    public function boot(GoogleTagManager $plugin);
}
