<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\App;

use Inpsyde\GoogleTagManager\App\PluginConfig;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Psr\Container\ContainerInterface;

/**
 * Class PluginConfigTest
 *
 * @package Inpsyde\GoogleTagManager\Tests\Unit\App
 */
class PluginConfigTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {
        $testee = new PluginConfig();
        static::assertInstanceOf(ContainerInterface::class, $testee);
    }

    /**
     * @test
     */
    public function testFreeze(): void
    {
        $testee = new PluginConfig();
        static::assertFalse($testee->isFrozen());
        static::assertInstanceOf(PluginConfig::class, $testee->freeze());
        static::assertTrue($testee->isFrozen());
    }

    /**
     * @test
     */
    public function testSetStop(): void
    {
        static::expectException(\Throwable::class);

        $testee = new PluginConfig();
        $testee->set('foo', 'bar');
        $testee->freeze();
        $testee->set('foo', 'baz');
    }

    /**
     * @test
     */
    public function testDeleteStop(): void
    {
        static::expectException(\Throwable::class);

        $testee = new PluginConfig();
        $testee->freeze();
        $testee->delete('foo');
    }

    /**
     * @test
     */
    public function testGetNotFound(): void
    {
        static::expectException(\Throwable::class);
        $testee = new PluginConfig();
        $testee->get('foo');
    }

    /**
     * @test
     */
    public function testSetGetHasDelete(): void
    {
        $expected_key = 'foo';
        $expected_value = 'bar';

        $testee = new PluginConfig();

        static::assertFalse($testee->has($expected_key));

        static::assertInstanceOf(PluginConfig::class, $testee->set($expected_key, $expected_value));

        static::assertTrue($testee->has($expected_key));
        static::assertSame($expected_value, $testee->get($expected_key));
        static::assertSame([$expected_key => $expected_value], $testee->all());

        static::assertInstanceOf(PluginConfig::class, $testee->delete($expected_key));

        static::assertFalse($testee->has($expected_key));
    }

    /**
     * @test
     */
    public function testImport(): void
    {
        $expected = ['foo' => 'bar'];

        $testee = new PluginConfig();

        static::assertInstanceOf(PluginConfig::class, $testee->import($expected));
        static::assertSame($expected, $testee->all());
    }

    /**
     * @test
     */
    public function testImportStop(): void
    {
        static::expectException(\Throwable::class);
        $testee = new PluginConfig();
        $testee->freeze();
        $testee->import(['foo']);
    }

    /**
     * @test
     */
    public function testImportNonObjectArray(): void
    {
        static::expectException(\Throwable::class);
        $testee = new PluginConfig();
        $testee->import('foo');
    }
}