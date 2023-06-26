<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\DataLayer;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\AuthorDataCollector;
use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecAwareInterface;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class AuthorDataCollectorTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {
        $settings = Mockery::mock(SettingsRepository::class);
        $settings->shouldReceive('option')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn([]);

        Functions\expect('is_single')
            ->andReturn(false);
        Functions\when('__')
            ->returnArg(1);

        $testee = new AuthorDataCollector($settings);

        static::assertInstanceOf(DataCollectorInterface::class, $testee);
        static::assertInstanceOf(SettingsSpecAwareInterface::class, $testee);
        static::assertFalse($testee->enabled());
        static::assertSame([], $testee->data());
        static::assertFalse($testee->isAllowed());
        static::assertNotempty($testee->settingsSpec());
    }
}