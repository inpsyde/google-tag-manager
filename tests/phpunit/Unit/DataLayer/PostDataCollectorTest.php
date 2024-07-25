<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\DataLayer;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\AuthorDataCollector;
use Inpsyde\GoogleTagManager\DataLayer\DataCollector;
use Inpsyde\GoogleTagManager\DataLayer\PostDataCollector;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecification;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class PostDataCollectorTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {

        Functions\expect('is_singular')
            ->andReturn(false);
        Functions\when('__')
            ->returnArg(1);

        $testee = PostDataCollector::new();

        static::assertInstanceOf(DataCollector::class, $testee);
        static::assertInstanceOf(SettingsSpecification::class, $testee);
        static::assertSame(null, $testee->data([]));
        static::assertNotempty($testee->specification());
    }
}