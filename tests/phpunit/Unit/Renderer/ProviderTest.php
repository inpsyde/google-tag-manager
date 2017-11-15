<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Renderer;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\Core\BootableProviderInterface;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Renderer\DataLayerRenderer;
use Inpsyde\GoogleTagManager\Renderer\GtmScriptTagRenderer;
use Inpsyde\GoogleTagManager\Renderer\NoscriptTagRenderer;
use Inpsyde\GoogleTagManager\Renderer\Provider;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractProviderTestCase;
use Mockery;
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
			'Renderer.GtmScriptTagRenderer' => GtmScriptTagRenderer::class,
			'Renderer.NoscriptTagRenderer'  => NoscriptTagRenderer::class,
			'Renderer.DataLayerRenderer'    => DataLayerRenderer::class
		];
	}

	/**
	 * {@inheritdoc}
	 */
	protected function mock_dependencies( Container $container ) {

		$container[ 'DataLayer' ] = Mockery::mock( DataLayer::class );
	}

	public function test_boot() {

		Functions\expect( 'is_admin' )
			->once()
			->andReturn( FALSE );

		/** @var BootableProviderInterface $testee */
		$testee    = $this->get_testee();
		$container = new Container();
		$this->mock_dependencies( $container );
		$testee->register( $container );
		$testee->boot( $container );

		static::assertTrue(
			has_action(
				GtmScriptTagRenderer::ACTION_BEFORE_SCRIPT,
				[ $container[ 'Renderer.DataLayerRenderer' ], 'render' ]
			)
		);

		static::assertTrue(
			has_action(
				'wp_head',
				[ $container[ 'Renderer.GtmScriptTagRenderer' ], 'render' ]
			)
		);

		static::assertTrue(
			has_action(
				NoscriptTagRenderer::ACTION_RENDER_NOSCRIPT,
				[ $container[ 'Renderer.NoscriptTagRenderer' ], 'render' ]
			)
		);

		static::assertTrue(
			has_action(
				'body_class',
				[ $container[ 'Renderer.NoscriptTagRenderer' ], 'render_at_body_start' ]
			)
		);
	}
}
