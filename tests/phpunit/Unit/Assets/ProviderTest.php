<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Assets;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\Assets\Provider;
use Inpsyde\GoogleTagManager\Assets\SettingsPage;
use Inpsyde\GoogleTagManager\Core\BootableProviderInterface;
use Inpsyde\GoogleTagManager\Core\PluginConfig;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractProviderTestCase;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ProviderTest extends AbstractProviderTestCase {

	/**
	 * @return ServiceProviderInterface
	 */
	protected function get_testee(): ServiceProviderInterface {

		return new Provider();
	}

	/**
	 * @return array
	 */
	protected function implemented_interfaces(): array {

		return [ ServiceProviderInterface::class, BootableProviderInterface::class ];
	}

	/**
	 * @return array
	 */
	protected function registered_services(): array {

		return [
			'Assets.SettingsPage' => SettingsPage::class
		];
	}

	/**
	 * {@inheritdoc}
	 */
	protected function mock_dependencies( \Pimple\Container $container ) {

		$container[ 'config' ] = \Mockery::mock( PluginConfig::class );
	}

	public function test_boot() {

		Functions\expect( 'is_admin' )
			->once()
			->andReturn( TRUE );

		/** @var BootableProviderInterface $testee */
		$testee    = $this->get_testee();
		$container = new Container();
		$this->mock_dependencies( $container );
		$testee->register( $container );
		$testee->boot( $container );

		static::assertTrue(
			has_action( 'admin_enqueue_scripts', [ $container[ 'Assets.SettingsPage' ], 'register_scripts' ] )
		);

		static::assertTrue(
			has_action( 'admin_enqueue_scripts', [ $container[ 'Assets.SettingsPage' ], 'register_styles' ] )
		);
	}
}