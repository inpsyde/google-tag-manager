<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\DataLayer\Site;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\DataLayer\User\UserDataCollector;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecAwareInterface;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class UserDataCollectorTest extends AbstractTestCase
{

    public function test_basic()
    {

        Functions\stubs(['__']);

        $settings = Mockery::mock(SettingsRepository::class);
        $settings->shouldReceive('getOption')
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
        static::assertSame(UserDataCollector::VISITOR_ROLE, $testee->visitorRole());
        static::assertEmpty($testee->fields());
        static::assertSame(["user" => ['isLoggedIn' => true]], $testee->data());
        static::assertFalse($testee->isAllowed());
        static::assertNotEmpty($testee->settingsSpec());
    }

    public function test_data()
    {

        $expected_logged_in = false;

        $expected_field_key   = 'foo';
        $expected_field_value = 'bar';

        $settings = Mockery::mock(SettingsRepository::class);
        $settings->shouldReceive('getOption')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn(
                [
                    UserDataCollector::SETTING__FIELDS => [
                        $expected_field_key,
                        'role'
                    ]
                ]
            );

        Functions\expect('is_user_logged_in')
            ->andReturn($expected_logged_in);

        Functions\expect('wp_get_current_user')
            ->andReturn(
                (object)[
                    $expected_field_key => $expected_field_value
                ]
            );

        static::assertSame(
            [
                'user' => [
                    $expected_field_key => $expected_field_value,
                    'role'              => UserDataCollector::VISITOR_ROLE,
                    'isLoggedIn'        => $expected_logged_in
                ]
            ],
            (new UserDataCollector($settings))->data()
        );
    }

    public function test_data__is_logged_in()
    {

        $expected_logged_in = true;

        $expected_field_key   = 'role';
        $expected_field_value = 'administrator';

        $expected = [
            'user' => [
                $expected_field_key => $expected_field_value,
                'isLoggedIn'        => $expected_logged_in
            ]
        ];

        Functions\expect('is_user_logged_in')
            ->andReturn($expected_logged_in);

        Functions\expect('wp_get_current_user')
            ->andReturn(
                (object)[
                    $expected_field_key => $expected_field_value,
                    'roles'             => [$expected_field_value]
                ]
            );

        $settings = Mockery::mock(SettingsRepository::class);
        $settings->shouldReceive('getOption')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn(
                [
                    UserDataCollector::SETTING__FIELDS => [
                        $expected_field_key
                    ]
                ]
            );

        static::assertSame($expected, (new UserDataCollector($settings))->data());
    }

}