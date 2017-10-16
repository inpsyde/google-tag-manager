<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Settings\Auth;

use Brain\Monkey\Functions;
use Brain\Nonces\ArrayContext;
use Brain\Nonces\NonceInterface;
use Inpsyde\GoogleTagManager\Settings\Auth\SettingsPageAuth;
use Inpsyde\GoogleTagManager\Settings\Auth\SettingsPageAuthInterface;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class SettingsPageAuthTest extends AbstractTestCase {

	public function test_basic() {

		$expected_name = 'foo';
		$testee        = new SettingsPageAuth( $expected_name );

		static::assertInstanceOf( SettingsPageAuthInterface::class, $testee );
		static::assertInstanceOf( NonceInterface::class, $testee->nonce() );
		static::assertSame( SettingsPageAuth::DEFAULT_CAP, $testee->cap() );
	}

	public function test_is_allowed() {

		Functions\stubs(
			[
				'current_user_can' => TRUE,
				'is_multisite'     => FALSE,
				'ms_is_switched'   => FALSE
			]
		);

		$nonce = Mockery::mock( NonceInterface::class );
		$nonce->shouldReceive( 'validate' )
			->once()
			->with( Mockery::type( ArrayContext::class ) )
			->andReturn( TRUE );

		static::assertTrue( ( new SettingsPageAuth( '', '', $nonce ) )->is_allowed() );
	}

	public function test_is_allowed__current_user_cannot() {

		Functions\expect( 'current_user_can' )
			->once()
			->with( Mockery::type( 'string' ) )
			->andReturn( FALSE );

		static::assertFalse( ( new SettingsPageAuth( '' ) )->is_allowed() );
	}

	public function test_is_allowed__multisite() {

		Functions\stubs( [ 'current_user_can' => TRUE ] );

		Functions\expect( 'is_multisite' )
			->once()
			->andReturn( TRUE );

		Functions\expect( 'ms_is_switched' )
			->once()
			->andReturn( TRUE );

		static::assertFalse( ( new SettingsPageAuth( '' ) )->is_allowed() );
	}
}