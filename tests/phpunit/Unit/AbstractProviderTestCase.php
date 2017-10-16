<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * @package Inpsyde\GoogleTagManager\Tests\Unit
 */
abstract class AbstractProviderTestCase extends AbstractTestCase {

	/**
	 * @return ServiceProviderInterface
	 */
	abstract protected function get_testee(): ServiceProviderInterface;

	/**
	 * @return array
	 */
	abstract protected function implemented_interfaces(): array;

	/**
	 * @return array
	 */
	abstract protected function registered_services(): array;

	/**
	 * @param Container $container
	 */
	protected function mock_dependencies( Container $container ) {

	}

	public function test_basic() {

		$container = new Container();
		$this->mock_dependencies( $container );

		$testee = $this->get_testee();
		$testee->register( $container );

		foreach ( $this->implemented_interfaces() as $interface ) {
			static::assertInstanceOf( $interface, $testee );
		}

		foreach ( $this->registered_services() as $name => $instance ) {
			static::assertArrayHasKey( $name, $container );
			static::assertInstanceOf( $instance, $container[ $name ] );
		}
	}

}