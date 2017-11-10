<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\DataLayer\Site;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\User\UserDataCollector;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecAwareInterface;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class UserDataCollectorTest extends AbstractTestCase {

	public function test_basic() {

		Functions\stubs( [ '__' ] );

		$settings = Mockery::mock( SettingsRepository::class );
		$settings->shouldReceive( 'get_option' )
			->once()
			->with( Mockery::type( 'string' ) )
			->andReturn( [] );

		$testee = new UserDataCollector( $settings );

		Functions\expect( 'is_user_logged_in' )
			->once()
			->andReturn( TRUE );

		Functions\expect( 'wp_get_current_user' )
			->once()
			->andReturn();

		static::assertInstanceOf( DataCollectorInterface::class, $testee );
		static::assertInstanceOf( SettingsSpecAwareInterface::class, $testee );
		static::assertFalse( $testee->enabled() );
		static::assertSame( UserDataCollector::VISITOR_ROLE, $testee->visitor_role() );
		static::assertEmpty( $testee->fields() );
		static::assertSame( [ "user" => [ 'isLoggedIn' => TRUE ] ], $testee->data() );
		static::assertFalse( $testee->is_allowed() );
		static::assertNotEmpty( $testee->settings_spec() );
	}

	public function test_data() {

		$expected_logged_in = FALSE;

		$expected_field_key   = 'foo';
		$expected_field_value = 'bar';

		$settings = Mockery::mock( SettingsRepository::class );
		$settings->shouldReceive( 'get_option' )
			->once()
			->with( Mockery::type( 'string' ) )
			->andReturn(
				[
					UserDataCollector::SETTING__FIELDS => [
						$expected_field_key,
						'role'
					]
				]
			);

		Functions\expect( 'is_user_logged_in' )
			->once()
			->andReturn( $expected_logged_in );

		Functions\expect( 'wp_get_current_user' )
			->once()
			->andReturn(
				(object) [
					$expected_field_key => $expected_field_value
				]
			);

		static::assertSame(
			[
				'user' => [
					$expected_field_key => $expected_field_value,
					'role'              => UserDataCollector::VISITOR_ROLE,
					'isLoggedIn'        => $expected_logged_in
				]
			],
			( new UserDataCollector( $settings ) )->data()
		);
	}

	public function test_data__is_logged_in() {

		$expected_logged_in = TRUE;

		$expected_field_key   = 'role';
		$expected_field_value = 'administrator';

		$settings = Mockery::mock( SettingsRepository::class );
		$settings->shouldReceive( 'get_option' )
			->once()
			->with( Mockery::type( 'string' ) )
			->andReturn(
				[
					UserDataCollector::SETTING__FIELDS => [
						$expected_field_key
					]
				]
			);

		Functions\expect( 'is_user_logged_in' )
			->once()
			->andReturn( $expected_logged_in );

		Functions\expect( 'wp_get_current_user' )
			->once()
			->andReturn(
				(object) [
					$expected_field_key => $expected_field_value,
					'roles'             => [ $expected_field_value ]
				]
			);

		static::assertSame(
			[
				'user' => [
					'role'       => $expected_field_value,
					'isLoggedIn' => $expected_logged_in
				]
			],
			( new UserDataCollector( $settings ) )->data()
		);
	}

}