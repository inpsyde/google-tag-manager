<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Core;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\Core\ConfigBuilder;
use Inpsyde\GoogleTagManager\Core\PluginConfig;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class ConfigBuilderTest extends AbstractTestCase {

	public function test_basic() {

		$testee = new ConfigBuilder();
		static::assertInstanceOf( ConfigBuilder::class, $testee );
	}

	public function test_plugin_from_file() {

		$expected_dir          = 'foo';
		$expected_url          = 'bar';
		$expected_header_key   = 'baz';
		$expected_header_value = 'qux';

		$testee = new ConfigBuilder();

		Functions\expect( 'get_file_data' )
			->once()
			->with( Mockery::type( 'string' ), Mockery::type( 'array' ) )
			->andReturn( [ $expected_header_key => $expected_header_value ] );

		Functions\expect( 'plugin_dir_path' )
			->once()
			->with( Mockery::type( 'string' ) )
			->andReturn( $expected_dir );

		Functions\expect( 'plugins_url' )
			->once()
			->with( Mockery::type( 'string' ), Mockery::type( 'string' ) )
			->andReturn( $expected_url );

		$config = $testee->plugin_from_file( '' );

		static::assertInstanceOf( PluginConfig::class, $config );
		static::assertSame( $expected_dir, $config->get( 'plugin.dir' ) );
		static::assertSame( $expected_url, $config->get( 'plugin.url' ) );
		static::assertSame( $expected_header_value, $config->get( $expected_header_key ) );
	}
}