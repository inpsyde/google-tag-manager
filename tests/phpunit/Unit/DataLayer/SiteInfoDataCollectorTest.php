<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\DataLayer;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\DataCollector;
use Inpsyde\GoogleTagManager\DataLayer\SiteInfoDataCollector;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecification;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class SiteInfoDataCollectorTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {
        Functions\stubs(['__', 'is_multisite' => false]);

        $testee = SiteInfoDataCollector::new($settings);

        static::assertInstanceOf(DataCollector::class, $testee);
        static::assertInstanceOf(SettingsSpecification::class, $testee);
        static::assertSame(null, $testee->data([]));
        static::assertNotempty($testee->specification());
    }

    /**
     * @test
     */
    public function testData(): void
    {
        $expected_ms_field = 'foo';
        $expected_ms_value = 'bar';

        $expected_info_field = 'baz';
        $expected_info_value = 'bam';

        $expected_output = [
            $expected_ms_field => $expected_ms_value,
            $expected_info_field => $expected_info_value,
        ];

        $settings = [
            SiteInfoDataCollector::SETTING__MULTISITE_FIELDS => [$expected_ms_field],
            SiteInfoDataCollector::SETTING__BLOG_INFO => [$expected_info_field],
        ];

        Functions\expect('is_multisite')
            ->once()
            ->andReturn(true);

        Functions\expect('get_blog_details')
            ->once()
            ->andReturn(
                (object) $expected_output
            );

        Functions\expect('get_bloginfo')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn($expected_info_value);

        static::assertSame(
            ["site" => $expected_output],
            SiteInfoDataCollector::new()->data($settings)
        );
    }
}