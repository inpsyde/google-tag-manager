<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Renderer;

use Brain\Monkey\Actions;
use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Renderer\GtmScriptTagRenderer;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class GtmScriptTagRendererTest extends AbstractTestCase {

	public function test_basic() {

		$testee = new GtmScriptTagRenderer( Mockery::mock( DataLayer::class ) );
		static::assertInstanceOf( GtmScriptTagRenderer::class, $testee );
	}

	public function test_render() {

		$expected_id   = 'GTM-123456';
		$expected_name = 'foo';

		$dataLayer = Mockery::mock( DataLayer::class );
		$dataLayer->shouldReceive( 'id' )
			->once()
			->andReturn( $expected_id );
		$dataLayer->shouldReceive( 'name' )
			->once()
			->andReturn( $expected_name );

		Functions\expect( 'esc_js' )
			->twice()
			->andReturnFirstArg();

		Actions\expectDone( GtmScriptTagRenderer::ACTION_BEFORE_SCRIPT )
			->once()
			->with( DataLayer::class );
		Actions\expectDone( GtmScriptTagRenderer::ACTION_AFTER_SCRIPT )
			->once()
			->with( DataLayer::class );

		$testee = new GtmScriptTagRenderer( $dataLayer );

		ob_start();
		static::assertTrue( $testee->render() );
		$output = ob_get_clean();

		static::assertContains( '<script>', $output );
		static::assertContains( $expected_id, $output );
		static::assertContains( $expected_name, $output );
		static::assertContains( '</script>', $output );
	}

	public function test_render__no_valid_id() {

		Actions\expectDone( 'inpsyde-google-tag-manager.error' )
			->once()
			->with( Mockery::type( 'string' ), Mockery::type( 'array' ) );

		$dataLayer = Mockery::mock( DataLayer::class );
		$dataLayer->shouldReceive( 'id' )
			->once()
			->andReturn( '' );

		$testee = new GtmScriptTagRenderer( $dataLayer );
		static::assertFalse( $testee->render() );
	}
}
