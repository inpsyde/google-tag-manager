<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\DataLayer;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecAwareInterface;
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

        $testee = new DataLayer($settings);

        static::assertInstanceOf(DataLayer::class, $testee);
        static::assertInstanceOf(SettingsSpecAwareInterface::class, $testee);
        static::assertSame('', $testee->id());
        static::assertSame(DataLayer::DATALAYER_NAME, $testee->name());
        static::assertTrue($testee->autoInsertNoscript());
        static::assertEmpty($testee->data());
        static::assertNotEmpty($testee->settingsSpec());
    }

    /**
     * @test
     */
    public function testAddGetData(): void
    {
        $settings = Mockery::mock(SettingsRepository::class);
        $settings->shouldReceive('option')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn([]);

        $valid_data = Mockery::mock(DataCollectorInterface::class);
        $valid_data->shouldReceive('isAllowed')
            ->once()
            ->andReturn(true);

        $invalid_data = Mockery::mock(DataCollectorInterface::class);
        $invalid_data->shouldReceive('isAllowed')
            ->once()
            ->andReturn(false);

        $testee = new DataLayer($settings);

        $testee->addData($valid_data);
        $testee->addData($invalid_data);

        $data = $testee->data();

        static::assertCount(1, $data);
        static::assertSame([$valid_data], $data);
    }
}