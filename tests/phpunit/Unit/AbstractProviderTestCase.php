<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * @package Inpsyde\GoogleTagManager\Tests\Unit
 */
abstract class AbstractProviderTestCase extends AbstractTestCase
{

    public function test_basic()
    {

        $testee = $this->get_testee();
        foreach ($this->implemented_interfaces() as $interface) {
            static::assertInstanceOf($interface, $testee);
        }

    }

    /**
     * @return ServiceProviderInterface
     */
    abstract protected function get_testee(): ServiceProviderInterface;

    /**
     * @return array
     */
    abstract protected function implemented_interfaces(): array;

    public function test_register()
    {

        $testee    = $this->get_testee();
        $container = new Container();
        $this->mock_dependencies($container);
        $testee->register($container);

        foreach ($this->registered_services() as $name => $instance) {
            static::assertArrayHasKey($name, $container);
            static::assertInstanceOf($instance, $container[ $name ]);
        }
    }

    /**
     * @param Container $container
     */
    protected function mock_dependencies(Container $container)
    {

    }

    /**
     * @return array
     */
    abstract protected function registered_services(): array;

}