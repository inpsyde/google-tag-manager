<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit;

use Brain\Monkey\Actions;
use Inpsyde\GoogleTagManager\App\Provider;
use Inpsyde\GoogleTagManager\App\BootableProvider;
use Inpsyde\GoogleTagManager\Event\BootstrapEvent;
use Inpsyde\GoogleTagManager\GoogleTagManager;
use Mockery;
use Psr\Container\ContainerInterface;

/**
 * @package Inpsyde\GoogleTagManager\Tests\Unit
 */
class GoogleTagManagerTest extends AbstractTestCase
{

    public function test_basic()
    {

        $testee = new GoogleTagManager();
        static::assertInstanceOf(ContainerInterface::class, $testee);
    }

    public function test_register()
    {

        $stub = Mockery::mock(Provider::class);
        $stub->shouldReceive('register')
            ->once();

        $testee = new GoogleTagManager();
        $testee->register($stub);

        static::assertCount(1, $testee->providers());
    }

    public function test_boot()
    {

        Actions\expectDone(BootstrapEvent::ACTION)
            ->once()
            ->with(Mockery::type(GoogleTagManager::class));

        $testee = new GoogleTagManager();

        static::assertTrue($testee->boot());
        static::assertFalse($testee->boot());
    }

    public function test_boot__bootable_provider()
    {

        $stub = Mockery::mock(Provider::class . ',' . BootableProvider::class);
        $stub->shouldReceive('register')
            ->once();
        $stub->shouldReceive('boot')
            ->once();

        $testee = new GoogleTagManager();
        $testee->register($stub);

        static::assertTrue($testee->boot());
    }

}