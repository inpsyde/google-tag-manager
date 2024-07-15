<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\DataLayer;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\AuthorDataCollector;
use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\SearchDataCollector;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecAwareInterface;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class SearchDataCollectorTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {
        $settings = [];

        Functions\expect('is_search')
            ->andReturn(false);
        Functions\when('__')
            ->returnArg(1);

        $testee = new SearchDataCollector($settings);

        static::assertInstanceOf(DataCollectorInterface::class, $testee);
        static::assertInstanceOf(SettingsSpecAwareInterface::class, $testee);
        static::assertSame(null, $testee->data());
        static::assertNotempty($testee->settingsSpec());
    }
}