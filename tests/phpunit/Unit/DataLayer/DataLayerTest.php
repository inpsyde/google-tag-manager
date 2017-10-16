<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\DataLayer;

use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class DataLayerTest extends AbstractTestCase {

	public function test_basic() {

		$settings = Mockery::mock( SettingsRepository::class );
		$settings->shouldReceive( 'get_option' )
			->once()
			->with( Mockery::type( 'string' ) )
			->andReturn( [] );

		$testee = new DataLayer( $settings );

		static::assertInstanceOf( DataLayer::class, $testee );
		static::assertSame( '', $testee->id() );
		static::assertSame( DataLayer::DATALAYER_NAME, $testee->name() );
		static::assertTrue( $testee->auto_insert_noscript() );
		static::assertEmpty( $testee->data() );
	}

	public function test_add_get_data() {

		$settings = Mockery::mock( SettingsRepository::class );
		$settings->shouldReceive( 'get_option' )
			->once()
			->with( Mockery::type( 'string' ) )
			->andReturn( [] );

		$valid_data = Mockery::mock( DataCollectorInterface::class );
		$valid_data->shouldReceive( 'is_allowed' )
			->once()
			->andReturn( TRUE );

		$invalid_data = Mockery::mock( DataCollectorInterface::class );
		$invalid_data->shouldReceive( 'is_allowed' )
			->once()
			->andReturn( FALSE );

		$testee = new DataLayer( $settings );

		$testee->add_data( $valid_data );
		$testee->add_data( $invalid_data );

		$data = $testee->data();

		static::assertCount( 1, $data );
		static::assertSame( [ $valid_data ], $data );
	}
}