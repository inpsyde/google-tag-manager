<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Settings;

use Brain\Monkey\Actions;
use Brain\Monkey\Functions;
use ChriCo\Fields\Element\ElementInterface;
use Inpsyde\Filter\FilterInterface;
use Inpsyde\GoogleTagManager\GoogleTagManager;
use Inpsyde\GoogleTagManager\Settings\Auth\SettingsPageAuthInterface;
use Inpsyde\GoogleTagManager\Settings\SettingsPage;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\View\SettingsPageViewInterface;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Inpsyde\Validator\ValidatorInterface;
use Mockery;

class SettingsPageTest extends AbstractTestCase {

	public function test_basic() {

		$view = Mockery::mock( SettingsPageViewInterface::class );
		$view->shouldReceive( 'name' )
			->once()
			->andReturn();

		$repo = Mockery::mock( SettingsRepository::class );
		$auth = Mockery::mock( SettingsPageAuthInterface::class );

		$testee = new SettingsPage( $view, $repo, $auth );
		static::assertInstanceOf( SettingsPage::class, $testee );
	}

	public function test_register() {

		$expected_hook = 'page-hook';

		Functions\expect( 'add_options_page' )
			->once()
			->with(
				Mockery::type( 'string' ),
				Mockery::type( 'string' ),
				Mockery::type( 'string' ),
				Mockery::type( 'string' ),
				Mockery::type( 'callable' )
			)
			->andReturn( $expected_hook );

		$view = Mockery::mock( SettingsPageViewInterface::class );
		$view->shouldReceive( 'name' )
			->times( 3 )
			->andReturn( 'foo' );
		$view->shouldReceive( 'slug' )
			->once()
			->andReturn( 'baz' );

		$auth = Mockery::mock( SettingsPageAuthInterface::class );
		$auth->shouldReceive( 'cap' )
			->once()
			->andReturn( 'bar' );

		$repo = Mockery::mock( SettingsRepository::class );
		$repo->shouldReceive( 'get_options' )
			->once()
			->andReturn( [] );

		Actions\expectAdded(
			'load-' . $expected_hook,
			'\Inpsyde\GoogleTagManager\Settings\SettingsPage->update()'
		);

		$testee = new SettingsPage( $view, $repo, $auth );

		static::assertTrue( $testee->register() );
	}

	public function test_update__not_allowed() {

		Functions\stubs( [ 'filter_input' => 'POST' ] );

		$view = Mockery::mock( SettingsPageViewInterface::class );
		$view->shouldReceive( 'name' )
			->andReturn();

		$repo = Mockery::mock( SettingsRepository::class );

		$auth = Mockery::mock( SettingsPageAuthInterface::class );
		$auth->shouldReceive( 'is_allowed' )
			->with( Mockery::type( 'array' ) )
			->andReturn( FALSE );

		static::assertFalse( ( new SettingsPage( $view, $repo, $auth ) )->update() );
	}

	public function test_update__wrong_request_method() {

		Functions\expect( 'filter_input' )
			->with( INPUT_SERVER, "REQUEST_METHOD", FILTER_SANITIZE_STRING )
			->andReturn( 'GET' );

		$view = Mockery::mock( SettingsPageViewInterface::class );
		$view->shouldReceive( 'name' )
			->andReturn();

		$repo = Mockery::mock( SettingsRepository::class );
		$auth = Mockery::mock( SettingsPageAuthInterface::class );

		static::assertFalse( ( new SettingsPage( $view, $repo, $auth ) )->update() );
	}

	public function test_update__update_fails() {

		Functions\stubs( [ 'filter_input' => 'POST' ] );

		\Brain\Monkey\Actions\expectDone( GoogleTagManager::ACTION_ERROR )
			->with( Mockery::type( 'string' ), Mockery::type( 'array' ) );

		$view = Mockery::mock( SettingsPageViewInterface::class );
		$view->shouldReceive( 'name' )
			->andReturn();

		$repo = Mockery::mock( SettingsRepository::class );
		$repo->shouldReceive( 'get_options' )
			->andReturn( [] );
		$repo->shouldReceive( 'update_options' )
			->with( Mockery::type( 'array' ) )
			->andReturn( FALSE );

		$auth = Mockery::mock( SettingsPageAuthInterface::class );
		$auth->shouldReceive( 'is_allowed' )
			->with( Mockery::type( 'array' ) )
			->andReturn( TRUE );

		static::assertFalse( ( new SettingsPage( $view, $repo, $auth ) )->update() );
	}

	public function test_add_element() {

		$view = Mockery::mock( SettingsPageViewInterface::class );
		$view->shouldReceive( 'name' )
			->once()
			->andReturn();

		$repo = Mockery::mock( SettingsRepository::class );

		$auth = Mockery::mock( SettingsPageAuthInterface::class );

		$element = Mockery::mock( ElementInterface::class );
		$element->shouldReceive( 'get_name' )
			->andReturn( '' );

		$filter    = Mockery::mock( FilterInterface::class );
		$validator = Mockery::mock( ValidatorInterface::class );

		$testee = new SettingsPage( $view, $repo, $auth );
		static::assertNull( $testee->add_element( $element, [ $filter ], [ $validator ] ) );
	}
}