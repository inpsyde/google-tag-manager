<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\DataLayer;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\DataCollector;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Service\DataCollectorRegistry;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecification;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class DataLayerTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {
        Functions\stubs(['__']);

        $settings = Mockery::mock(SettingsRepository::class);
        $settings->shouldReceive('option')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn([]);

        $dataCollectorRegistry = Mockery::mock(DataCollectorRegistry::class);
        $dataCollectorRegistry->expects('all')
            ->zeroOrMoreTimes()
            ->andReturn([]);

        $testee = DataLayer::new($settings, $dataCollectorRegistry);

        static::assertInstanceOf(DataLayer::class, $testee);
        static::assertInstanceOf(SettingsSpecification::class, $testee);
        static::assertSame('dataLayer', $testee->id());
        static::assertSame('', $testee->gtmId());
        static::assertSame(DataLayer::DATALAYER_NAME, $testee->dataLayerName());
        static::assertTrue($testee->autoInsertNoscript());
        static::assertEmpty($testee->data());
        static::assertNotEmpty($testee->specification());
    }
}