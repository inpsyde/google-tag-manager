<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Assets;

use Inpsyde\GoogleTagManager\Core\PluginConfig;
use Inpsyde\GoogleTagManager\Exception\NotFoundException;

/**
 * @package Inpsyde\GoogleTagManager
 */
class SettingsPage
{

    /**
     * @var PluginConfig
     */
    private $config;

    /**
     * Backend constructor.
     *
     * @param PluginConfig $config
     */
    public function __construct(PluginConfig $config)
    {

        $this->config = $config;
    }

    /**
     * Load the admin scripts.
     *
     * @throws NotFoundException
     *
     * @return    bool
     */
    public function registerScripts(): bool
    {

        wp_enqueue_script(
            'inpsyde-google-tag-manager-admin-scripts',
            $this->config->get('assets.js.url') . 'admin' . $this->config->get('assets.suffix') . '.js',
            ['jquery-ui-tabs'],
            $this->config->get('plugin.version'),
            true
        );

        return true;
    }

    /**
     * Load the admin style
     *
     * @throws NotFoundException
     *
     * @return    bool
     */
    public function registerStyles(): bool
    {

        wp_enqueue_style(
            'inpsyde-google-tag-manager-admin-styles',
            $this->config->get('assets.css.url') . 'admin' . $this->config->get('assets.suffix') . '.css',
            [],
            $this->config->get('plugin.version')
        );

        return true;
    }
}
