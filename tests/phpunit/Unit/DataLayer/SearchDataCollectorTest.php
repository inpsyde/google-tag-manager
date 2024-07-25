<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\DataLayer;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\AuthorDataCollector;
use Inpsyde\GoogleTagManager\DataLayer\DataCollector;
use Inpsyde\GoogleTagManager\DataLayer\SearchDataCollector;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecification;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class SearchDataCollectorTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {

        Functions\expect('is_search')
            ->andReturn(false);
        Functions\when('__')
            ->returnArg(1);

        $testee = SearchDataCollector::new();

        static::assertInstanceOf(DataCollector::class, $testee);
        static::assertInstanceOf(SettingsSpecification::class, $testee);
        static::assertSame(null, $testee->data([]));
        static::assertNotempty($testee->specification());
    }
}