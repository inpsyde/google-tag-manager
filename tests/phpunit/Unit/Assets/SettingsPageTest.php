<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Assets;

use Brain\Monkey\Functions;
use Inpsyde\GoogleTagManager\Assets\SettingsPage;
use Inpsyde\GoogleTagManager\Core\PluginConfig;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractTestCase;
use Mockery;

class SettingsPageTest extends AbstractTestCase
{

    public function test_basic()
    {

        $config = Mockery::mock(PluginConfig::class);
        $config->shouldReceive('get')
            ->with(Mockery::type('string'))
            ->andReturnUsing(
                function ($args) {

                    return $args[ 0 ];
                }
            );

        Functions\expect('wp_enqueue_script')
            ->once()
            ->with(
                Mockery::type('string'),
                Mockery::type('string'),
                Mockery::type('array'),
                Mockery::type('string'),
                Mockery::type('bool')
            );

        Functions\expect('wp_enqueue_style')
            ->once()
            ->with(
                Mockery::type('string'),
                Mockery::type('string'),
                Mockery::type('array'),
                Mockery::type('string')
            );

        $testee = new SettingsPage($config);
        static::assertTrue($testee->registerScripts());
        static::assertTrue($testee->registerStyles());
    }

}