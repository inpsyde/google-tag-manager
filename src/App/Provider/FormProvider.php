<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\App\Provider;

use ChriCo\Fields\ElementFactory;
use Inpsyde\GoogleTagManager\GoogleTagManager;

/**
 * @package Inpsyde\GoogleTagManager\App\Provider
 */
final class FormProvider implements \Inpsyde\GoogleTagManager\App\Provider
{

    /**
     * @param GoogleTagManager $plugin
     *
     * @throws \Inpsyde\GoogleTagManager\Exception\AlreadyBootedException
     */
    public function register(GoogleTagManager $plugin)
    {
        $plugin->set(
            'ChriCo.Fields.ElementFactory',
            function (): ElementFactory {
                return new ElementFactory();
            }
        );
    }
}
