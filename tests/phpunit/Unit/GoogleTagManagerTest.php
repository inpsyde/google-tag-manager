<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit;

use Brain\Monkey\Actions;
use Inpsyde\GoogleTagManager\Core\BootableProviderInterface;
use Inpsyde\GoogleTagManager\GoogleTagManager;
use Mockery;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * @package Inpsyde\GoogleTagManager\Tests\Unit
 */
class GoogleTagManagerTest extends AbstractTestCase {

	public function test_basic() {

		$testee = new GoogleTagManager();
		static::assertInstanceOf( \ArrayAccess::class, $testee );
		static::assertInstanceOf( Container::class, $testee );
	}

	public function test_constructor() {

		$key   = 'foo';
		$value = 'bar';

		$testee = new GoogleTagManager( [ $key => $value ] );
		static::assertSAme( $value, $testee[ $key ] );
	}

	public function test_register() {

		$stub = Mockery::mock( ServiceProviderInterface::class );
		$stub->shouldReceive( 'register' )
			->once();

		$testee = new GoogleTagManager();
		$testee->register( $stub, [ 'foo' => 'bar' ] );

		static::assertCount( 1, $testee->providers() );
	}

	public function test_boot() {

		Actions\expectDone( GoogleTagManager::ACTION_BOOT )
			->once()
			->with( Mockery::type( GoogleTagManager::class ) );

		$testee = new GoogleTagManager();

		static::assertTrue( $testee->boot() );
		static::assertFalse( $testee->boot() );
	}

	public function test_boot__bootable_provider() {

		$stub = Mockery::mock( ServiceProviderInterface::class . ',' . BootableProviderInterface::class );
		$stub->shouldReceive( 'register' )
			->once();
		$stub->shouldReceive( 'boot' )
			->once();

		$testee = new GoogleTagManager();
		$testee->register( $stub );

		static::assertTrue( $testee->boot() );
	}

}