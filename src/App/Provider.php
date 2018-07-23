<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\App;

use Inpsyde\GoogleTagManager\GoogleTagManager;

/**
 * Interface Provider
 *
 * @package Inpsyde\GoogleTagManager\App
 */
interface Provider
{

    /**
     * @param GoogleTagManager $plugin
     */
    public function register(GoogleTagManager $plugin);
}
