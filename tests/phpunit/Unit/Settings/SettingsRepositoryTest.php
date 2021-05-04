<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Settings;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;

class SettingsRepositoryTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {
        $testee = new SettingsRepository('');
        static::assertInstanceOf(SettingsRepository::class, $testee);
    }

    /**
     * @test
     */
    public function testUpdateOptions(): void
    {
        $testee = new SettingsRepository('foo');

        Functions\expect('update_option')
            ->once()
            ->with(\Mockery::type('string'), \Mockery::type('array'))
            ->andReturn(true);

        static::assertTrue($testee->update([]));
    }

    /**
     * @test
     */
    public function testGetOptions(): void
    {
        $expected_key = 'foo';
        $expected = ['bar' => 'baz'];
        $testee = new SettingsRepository($expected_key);

        Functions\expect('get_option')
            ->once()
            ->with(\Mockery::type('string'), \Mockery::type('array'))
            ->andReturn($expected);

        static::assertSame($expected, $testee->options());
    }

    /**
     * @test
     */
    public function testGetOption(): void
    {
        $expected = ['bar' => 'baz'];
        $testee = new SettingsRepository('');

        Functions\stubs(['get_option' => $expected]);

        static::assertSame('baz', $testee->option('bar'));
    }
}