<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager;

use Inpsyde\GoogleTagManager\Core\BootableProviderInterface;
use Inpsyde\GoogleTagManager\Event\BootstrapEvent;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * @package Inpsyde\GoogleTagManager
 */
final class GoogleTagManager extends Container
{

    /**
     * @var bool
     */
    private $booted = false;

    /**
     * @var array
     */
    private $providers = [];

    /**
     * Registers a service provider.
     *
     * @param ServiceProviderInterface $provider A ServiceProviderInterface instance.
     * @param array                    $values   An array of values that customizes the provider.
     *
     * @return GoogleTagManager
     */
    public function register(ServiceProviderInterface $provider, array $values = [])
    {

        $this->providers[] = $provider;
        $provider->register($this);

        foreach ($values as $key => $value) {
            $this[ $key ] = $value;
        }

        return $this;
    }

    /**
     * Boots all service providers.
     *
     * This method is automatically called by handle(), but you can use it
     * to boot all service providers when not handling a request.
     *
     * @return bool
     */
    public function boot(): bool
    {

        if ($this->booted) {
            return false;
        }
        $this->booted = true;

        /**
         * Fires right before GoogleTagManager gets bootstrapped.
         *
         * Hook here to register custom service providers.
         *
         * @param GoogleTagManager
         */
        do_action(BootstrapEvent::ACTION, $this);

        foreach ($this->providers as $provider) {
            if ($provider instanceof BootableProviderInterface) {
                $provider->boot($this);
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function providers(): array
    {

        return $this->providers;
    }
}
