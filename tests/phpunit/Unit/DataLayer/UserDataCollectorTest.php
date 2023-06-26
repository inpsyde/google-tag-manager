<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\DataLayer;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\UserDataCollector;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecAwareInterface;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class UserDataCollectorTest extends AbstractTestCase
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

        $testee = new UserDataCollector($settings);

        Functions\expect('is_user_logged_in')
            ->andReturn(true);

        Functions\expect('wp_get_current_user')
            ->andReturn();

        static::assertInstanceOf(DataCollectorInterface::class, $testee);
        static::assertInstanceOf(SettingsSpecAwareInterface::class, $testee);
        static::assertFalse($testee->enabled());
        static::assertEmpty($testee->fields());
        static::assertSame(["user" => ['isLoggedIn' => true]], $testee->data());
        static::assertFalse($testee->isAllowed());
        static::assertNotEmpty($testee->settingsSpec());
    }

    /**
     * @test
     */
    public function testData(): void
    {
        $expected_logged_in = false;

        $settings = Mockery::mock(SettingsRepository::class);
        $settings->shouldReceive('option')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn([]);

        Functions\expect('is_user_logged_in')
            ->andReturn($expected_logged_in);

        static::assertSame(
            [
                'user' => [
                    'isLoggedIn' => $expected_logged_in,
                ],
            ],
            (new UserDataCollector($settings))->data()
        );
    }

    /**
     * @test
     */
    public function testDataWithVisitorRole(): void
    {
        $expected_role = 'foo';
        $expected_logged_in = false;

        $settings = Mockery::mock(SettingsRepository::class);
        $settings->shouldReceive('option')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn(
                [
                    UserDataCollector::SETTING__VISITOR_ROLE => $expected_role,
                ]
            );

        Functions\expect('is_user_logged_in')
            ->andReturn($expected_logged_in);

        static::assertSame(
            [
                'user' => [
                    'role' => $expected_role,
                    'isLoggedIn' => $expected_logged_in,
                ],
            ],
            (new UserDataCollector($settings))->data()
        );
    }

    /**
     * @test
     */
    public function testDataIsLoggedIn(): void
    {
        $expected_logged_in = true;

        $expected_field_key = 'role';
        $expected_field_value = 'administrator';

        $expected = [
            'user' => [
                $expected_field_key => $expected_field_value,
                'isLoggedIn' => $expected_logged_in,
            ],
        ];

        Functions\expect('is_user_logged_in')
            ->andReturn($expected_logged_in);

        Functions\expect('wp_get_current_user')
            ->andReturn(
                (object) [
                    $expected_field_key => $expected_field_value,
                    'roles' => [$expected_field_value],
                ]
            );

        $settings = Mockery::mock(SettingsRepository::class);
        $settings->shouldReceive('option')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn(
                [
                    UserDataCollector::SETTING__FIELDS => [
                        $expected_field_key,
                    ],
                ]
            );

        static::assertSame($expected, (new UserDataCollector($settings))->data());
    }
}