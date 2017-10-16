<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Assets;

use Inpsyde\GoogleTagManager\Assets\Provider;
use Inpsyde\GoogleTagManager\Assets\SettingsPage;
use Inpsyde\GoogleTagManager\Core\BootableProviderInterface;
use Inpsyde\GoogleTagManager\Core\PluginConfig;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractProviderTestCase;
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
}