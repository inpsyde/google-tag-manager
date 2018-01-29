<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Settings;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;

class SettingsRepositoryTest extends AbstractTestCase
{

    public function test_basic()
    {

        $testee = new SettingsRepository('');
        static::assertInstanceOf(SettingsRepository::class, $testee);
    }

    public function test_update_options()
    {

        $testee = new SettingsRepository('foo');

        Functions\expect('update_option')
            ->once()
            ->with(\Mockery::type('string'), \Mockery::type('array'))
            ->andReturn(true);

        static::assertTrue($testee->updateOptions([]));
    }

    public function test_get_options()
    {

        $expected_key = 'foo';
        $expected     = ['bar' => 'baz'];
        $testee       = new SettingsRepository($expected_key);

        Functions\expect('get_option')
            ->once()
            ->with(\Mockery::type('string'), \Mockery::type('array'))
            ->andReturn($expected);

        static::assertSame($expected, $testee->getOptions());
    }

    public function test_get_option()
    {

        $expected = ['bar' => 'baz'];
        $testee   = new SettingsRepository('');

        Functions\stubs(['get_option' => $expected]);

        static::assertSame('baz', $testee->getOption('bar'));
    }

}