<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\DataLayer;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\DataCollector;
use Inpsyde\GoogleTagManager\DataLayer\UserDataCollector;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecification;
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

        $testee = UserDataCollector::new();

        Functions\expect('is_user_logged_in')
            ->andReturn(true);

        Functions\expect('wp_get_current_user')
            ->andReturn();

        static::assertInstanceOf(DataCollector::class, $testee);
        static::assertInstanceOf(SettingsSpecification::class, $testee);
        static::assertSame(["user" => ['isLoggedIn' => true]], $testee->data([]));
        static::assertNotEmpty($testee->specification());
    }

    /**
     * @test
     */
    public function testData(): void
    {
        $expected_logged_in = false;

        $settings = [];

        Functions\expect('is_user_logged_in')
            ->andReturn($expected_logged_in);

        static::assertSame(
            [
                'user' => [
                    'role' => 'visitor',
                    'isLoggedIn' => $expected_logged_in,
                ],
            ],
            UserDataCollector::new()->data($settings)
        );
    }

    /**
     * @test
     */
    public function testDataWithVisitorRole(): void
    {
        $expected_role = 'foo';
        $expected_logged_in = false;

        $settings = [
            UserDataCollector::SETTING__VISITOR_ROLE => $expected_role,
        ];

        Functions\expect('is_user_logged_in')
            ->andReturn($expected_logged_in);

        static::assertSame(
            [
                'user' => [
                    'role' => $expected_role,
                    'isLoggedIn' => $expected_logged_in,
                ],
            ],
            UserDataCollector::new()->data($settings)
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

        $settings = [
            UserDataCollector::SETTING__FIELDS => [
                $expected_field_key,
            ],
        ];

        static::assertSame($expected, UserDataCollector::new()->data($settings));
    }
}