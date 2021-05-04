<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\App;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\App\ConfigBuilder;
use Inpsyde\GoogleTagManager\App\PluginConfig;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

/**
 * Class ConfigBuilderTest
 *
 * @package Inpsyde\GoogleTagManager\Tests\Unit\App
 */
class ConfigBuilderTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {
        $testee = new ConfigBuilder();
        static::assertInstanceOf(ConfigBuilder::class, $testee);
    }

    /**
     * @test
     */
    public function testFromFile(): void
    {
        $expected_dir = 'foo';
        $expected_url = 'bar';
        $expected_header_key = 'baz';
        $expected_header_value = 'qux';

        $testee = new ConfigBuilder();

        Functions\expect('get_file_data')
            ->once()
            ->with(Mockery::type('string'), Mockery::type('array'))
            ->andReturn([$expected_header_key => $expected_header_value]);

        Functions\expect('plugin_dir_path')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn($expected_dir);

        Functions\expect('plugins_url')
            ->once()
            ->with(Mockery::type('string'), Mockery::type('string'))
            ->andReturn($expected_url);

        $config = $testee->fromFile('');

        static::assertInstanceOf(PluginConfig::class, $config);
        static::assertSame($expected_dir, $config->get('plugin.dir'));
        static::assertSame($expected_url, $config->get('plugin.url'));
        static::assertSame($expected_header_value, $config->get($expected_header_key));
    }
}