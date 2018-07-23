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

    public function test_basic()
    {

        $testee = new PluginConfig();
        static::assertInstanceOf(ContainerInterface::class, $testee);
    }

    public function test_freeze()
    {

        $testee = new PluginConfig();
        static::assertFalse($testee->isFrozen());
        static::assertInstanceOf(PluginConfig::class, $testee->freeze());
        static::assertTrue($testee->isFrozen());
    }

    /**
     * @expectedException \Throwable
     */
    public function test_set_stop()
    {

        $testee = new PluginConfig();
        $testee->set('foo', 'bar');
        $testee->freeze();
        $testee->set('foo', 'baz');
    }

    /**
     * @expectedException \Throwable
     */
    public function test_delete_stop()
    {

        $testee = new PluginConfig();
        $testee->freeze();
        $testee->delete('foo');
    }

    /**
     * @expectedException \Throwable
     */
    public function test_get_not_found()
    {

        $testee = new PluginConfig();
        $testee->get('foo');
    }

    public function test_set_get_has_delete()
    {

        $expected_key   = 'foo';
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

    public function test_import()
    {

        $expected = ['foo' => 'bar'];

        $testee = new PluginConfig();

        static::assertInstanceOf(PluginConfig::class, $testee->import($expected));
        static::assertSame($expected, $testee->all());
    }

    /**
     * @expectedException \Throwable
     */
    public function test_import_stop()
    {

        $testee = new PluginConfig();
        $testee->freeze();
        $testee->import(['foo']);
    }

    /**
     * @expectedException \Throwable
     */
    public function test_import_non_object_array()
    {

        $testee = new PluginConfig();
        $testee->import('foo');
    }
}