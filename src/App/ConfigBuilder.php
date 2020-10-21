<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\App;

use Inpsyde\GoogleTagManager\Exception\ConfigAlreadyFrozenException;
use Inpsyde\GoogleTagManager\Exception\NotFoundException;

/**
 * @package Inpsyde\GoogleTagManager\App
 */
final class ConfigBuilder
{

    /**
     * Creating the Plugin-Config by given $file in constructor.
     *
     * @param string $file
     *
     * @throws NotFoundException
     * @throws ConfigAlreadyFrozenException
     *
     * @return PluginConfig $config
     */
    public static function fromFile(string $file): PluginConfig
    {
        $config = new PluginConfig();

        $config->import(
            [
                'debug.display' => defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY,
                'debug.mode' => defined('WP_DEBUG') && WP_DEBUG,
                'debug.script_mode' => defined('SCRIPT_DEBUG') && SCRIPT_DEBUG,
            ]
        );

        $config->import(self::generatePluginHeaders($file));

        $config->import(
            [
                'assets.suffix' => $config->get('debug.mode')
                    ? ''
                    : '.min',
                'assets.url' => $config->get('plugin.url').'assets/',
            ]
        );

        return $config;
    }

    /**
     * Internal function to create the plugin_headers for $config.
     *
     * @param string $file
     *
     * @return array $plugin_headers
     */
    private static function generatePluginHeaders(string $file): array
    {
        if (defined('ABSPATH')) {
            $pluginsFile = ABSPATH.'/wp-admin/includes/plugin.php';
            if (! function_exists('get_config') && file_exists($pluginsFile)) {
                require_once($pluginsFile);
            }
        }

        $defaultHeaders = [
            'plugin.name' => 'Plugin Name',
            'plugin.uri' => 'Plugin URI',
            'plugin.description' => 'Description',
            'plugin.author' => 'Author',
            'plugin.version' => 'Version',
            'plugin.textdomain' => 'Text Domain',
            'plugin.textdomain.path' => 'Domain Path',
        ];

        $headers = get_file_data($file, $defaultHeaders);

        $headers['plugin.dir'] = plugin_dir_path($file);
        $headers['plugin.file'] = $file;
        $headers['plugin.url'] = plugins_url('/', $file);

        return $headers;
    }
}
