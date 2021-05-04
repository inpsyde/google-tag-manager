<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Settings\Auth;

use Brain\Monkey\Functions;
use Brain\Nonces\ArrayContext;
use Brain\Nonces\NonceInterface;
use Inpsyde\GoogleTagManager\Event\LogEvent;
use Inpsyde\GoogleTagManager\Settings\Auth\SettingsPageAuth;
use Inpsyde\GoogleTagManager\Settings\Auth\SettingsPageAuthInterface;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class SettingsPageAuthTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {
        $expected_name = 'foo';
        $testee = new SettingsPageAuth($expected_name);

        static::assertInstanceOf(SettingsPageAuthInterface::class, $testee);
        static::assertInstanceOf(NonceInterface::class, $testee->nonce());
        static::assertSame(SettingsPageAuth::DEFAULT_CAP, $testee->cap());
    }

    /**
     * @test
     */
    public function testIsAllowed(): void
    {
        Functions\stubs(
            [
                'current_user_can' => true,
                'is_multisite' => false,
                'ms_is_switched' => false,
            ]
        );

        $nonce = Mockery::mock(NonceInterface::class);
        $nonce->shouldReceive('validate')
            ->once()
            ->with(Mockery::type(ArrayContext::class))
            ->andReturn(true);

        static::assertTrue((new SettingsPageAuth('', '', $nonce))->isAllowed());
    }

    /**
     * @test
     */
    public function testIsAllowedCurrentUserCannot(): void
    {
        \Brain\Monkey\Actions\expectDone(LogEvent::ACTION)
            ->once();

        Functions\expect('current_user_can')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn(false);

        static::assertFalse((new SettingsPageAuth(''))->isAllowed());
    }

    /**
     * @test
     */
    public function testIsAllowedMultisite(): void
    {
        Functions\stubs(['current_user_can' => true]);

        Functions\expect('is_multisite')
            ->once()
            ->andReturn(true);

        Functions\expect('ms_is_switched')
            ->once()
            ->andReturn(true);

        static::assertFalse((new SettingsPageAuth(''))->isAllowed());
    }
}