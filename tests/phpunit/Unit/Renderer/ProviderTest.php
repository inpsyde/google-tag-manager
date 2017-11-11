<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Renderer;

use Inpsyde\GoogleTagManager\Core\BootableProviderInterface;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Renderer\DataLayerRenderer;
use Inpsyde\GoogleTagManager\Renderer\NoscriptTagRenderer;
use Inpsyde\GoogleTagManager\Renderer\Provider;
use Inpsyde\GoogleTagManager\Renderer\GtmScriptTagRenderer;
use Inpsyde\GoogleTagManager\Renderer\SnippetGenerator;
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
			'Renderer.GtmScriptTagRenderer'   => GtmScriptTagRenderer::class,
			'Renderer.NoscriptTagRenderer' => NoscriptTagRenderer::class,
			'Renderer.DataLayerRenderer'   => DataLayerRenderer::class
		];
	}

	/**
	 * {@inheritdoc}
	 */
	protected function mock_dependencies( Container $container ) {

		$container[ 'DataLayer' ] = Mockery::mock( DataLayer::class );
	}
}
