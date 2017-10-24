<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Renderer;

use Brain\Monkey\Actions;
use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Renderer\SnippetGenerator;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class SnippetGeneratorTest extends AbstractTestCase {

	public function test_basic() {

		$testee = new SnippetGenerator( Mockery::mock( DataLayer::class ) );
		static::assertInstanceOf( SnippetGenerator::class, $testee );
	}

	public function test_render_data_layer() {

		$expected_data = [ 'foo' => 'bar' ];
		$expected_name = 'baz';

		$data = Mockery::mock( DataCollectorInterface::class );
		$data->shouldReceive( 'data' )
			->once()
			->andReturn( $expected_data );

		$dataLayer = Mockery::mock( DataLayer::class );
		$dataLayer->shouldReceive( 'name' )
			->once()
			->andReturn( $expected_name );
		$dataLayer->shouldReceive( 'data' )
			->once()
			->andReturn( [ $data ] );

		Functions\expect( 'esc_js' )
			->with( Mockery::type( 'string' ) )
			->andReturnFirstArg();

		$testee = new SnippetGenerator( $dataLayer );

		ob_start();
		static::assertTrue( $testee->render_data_layer() );
		$output = ob_get_clean();

		static::assertContains( '<script>', $output );
		static::assertContains( 'var ' . $expected_name, $output );
		static::assertContains( $expected_name . '.push(', $output );
		static::assertContains( json_encode( $expected_data ), $output );
		static::assertContains( '</script>', $output );
	}

	public function test_render_gtm_script() {

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

		$testee = new SnippetGenerator( $dataLayer );

		ob_start();
		static::assertTrue( $testee->render_gtm_script() );
		$output = ob_get_clean();

		static::assertContains( '<script>', $output );
		static::assertContains( $expected_id, $output );
		static::assertContains( $expected_name, $output );
		static::assertContains( '</script>', $output );
	}

	public function test_render_gtm_script__no_valid_id() {

		Actions\expectDone( 'inpsyde-google-tag-manager.error' )
			->once()
			->with( Mockery::type( 'string' ), Mockery::type( 'array' ) );

		$dataLayer = Mockery::mock( DataLayer::class );
		$dataLayer->shouldReceive( 'id' )
			->once()
			->andReturn( '' );

		$testee = new SnippetGenerator( $dataLayer );
		static::assertFalse( $testee->render_gtm_script() );
	}

	public function test_render_noscript() {

		$expected_id   = 'GTM-123456';
		$expected_data = [ 'foo' => 'bar' ];

		$first_url    = SnippetGenerator::GTM_NOSCRIPT_URL . '?id=' . $expected_id;
		$expected_url = $first_url . '&foo=bar';

		$data = Mockery::mock( DataCollectorInterface::class );
		$data->shouldReceive( 'data' )
			->once()
			->andReturn( $expected_data );

		$dataLayer = Mockery::mock( DataLayer::class );
		$dataLayer->shouldReceive( 'id' )
			->once()
			->andReturn( $expected_id );
		$dataLayer->shouldReceive( 'data' )
			->once()
			->andReturn( [ $data ] );

		$testee = new SnippetGenerator( $dataLayer );

		Functions\expect( 'add_query_arg' )
			->once()
			->with( [ 'id' => $expected_id ], SnippetGenerator::GTM_NOSCRIPT_URL )
			->andReturn( $first_url )
			->andAlsoExpectIt( 'add_query_arg' )
			->once()
			->with( $expected_data, $first_url )
			->andReturn( $expected_url );

		ob_start();
		$testee->render_noscript();
		$output = ob_get_clean();

		static::assertContains( '<noscript>', $output );
		static::assertContains( '<iframe', $output );
		static::assertContains( $expected_url, $output );
		static::assertContains( '</iframe>', $output );
		static::assertContains( '</noscript>', $output );
	}

	public function test_render_noscript__invalid_id() {

		Actions\expectDone( 'inpsyde-google-tag-manager.error' )
			->once()
			->with( Mockery::type( 'string' ), Mockery::type( 'array' ) );

		$dataLayer = Mockery::mock( DataLayer::class );
		$dataLayer->shouldReceive( 'id' )
			->once()
			->andReturn( '' );

		$testee = new SnippetGenerator( $dataLayer );

		ob_start();
		$testee->render_noscript();
		$output = ob_get_clean();

		static::assertSame( '', $output );
	}

	public function test_render_noscript_at_body_start__no_auto_insert() {

		$dataLayer = Mockery::mock( DataLayer::class );
		$dataLayer->shouldReceive( 'auto_insert_noscript' )
			->once()
			->andReturn( FALSE );

		$testee = new SnippetGenerator( $dataLayer );

		static::assertEmpty( $testee->render_noscript_at_body_start( [] ) );
	}

	public function test_render_noscript_at_body_start__no_noscript() {

		$dataLayer = Mockery::mock( DataLayer::class );
		$dataLayer->shouldReceive( 'auto_insert_noscript' )
			->once()
			->andReturn( TRUE );
		$dataLayer->shouldReceive( 'id' )
			->once()
			->andReturn( '' );

		$testee = new SnippetGenerator( $dataLayer );

		static::assertEmpty( $testee->render_noscript_at_body_start( [] ) );
	}

	public function test_render_noscript_at_body_start() {

		$expected_id = 'GTM-123456';

		$dataLayer = Mockery::mock( DataLayer::class );
		$dataLayer->shouldReceive( 'auto_insert_noscript' )
			->once()
			->andReturn( TRUE );
		$dataLayer->shouldReceive( 'id' )
			->once()
			->andReturn( $expected_id );
		$dataLayer->shouldReceive( 'data' )
			->once()
			->andReturn( [] );

		Functions\stubs( [ 'add_query_arg' => '' ] );

		$testee = new SnippetGenerator( $dataLayer );

		$output = $testee->render_noscript_at_body_start( [] );
		static::assertContains( '">', $output[ 0 ] );
		static::assertContains( '<br', $output[ 0 ] );
	}
}