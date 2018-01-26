<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\Settings;

use Brain\Monkey\Functions;
use ChriCo\Fields\ViewFactory;
use Inpsyde\GoogleTagManager\Core\BootableProviderInterface;
use Inpsyde\GoogleTagManager\Core\PluginConfig;
use Inpsyde\GoogleTagManager\Settings\Provider;
use Inpsyde\GoogleTagManager\Settings\SettingsPage;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractProviderTestCase;
use Mockery;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ProviderTest extends AbstractProviderTestCase
{

    public function test_boot()
    {

        Functions\expect('is_admin')
            ->once()
            ->andReturn(true);

        /** @var BootableProviderInterface $testee */
        $testee    = $this->get_testee();
        $container = new Container();
        $this->mock_dependencies($container);
        $testee->register($container);
        $testee->boot($container);

        static::assertTrue(
            has_action(
                'admin_menu',
                [$container[ 'Settings.Page' ], 'register']
            )
        );
    }

    /**
     * @return ServiceProviderInterface
     */
    protected function get_testee(): ServiceProviderInterface
    {

        return new Provider();
    }

    /**
     * {@inheritdoc}
     */
    protected function mock_dependencies(Container $container)
    {

        Functions\stubs(['__']);

        $config = Mockery::mock(PluginConfig::class);
        $config->shouldReceive('get')
            ->andReturnUsing(
                function ($args) {

                    return $args[ 0 ];
                }
            );
        $container[ 'config' ]                    = $config;
        $container[ 'ChriCo.Fields.ViewFactory' ] = Mockery::mock(ViewFactory::class);
    }

    /**
     * @return array
     */
    protected function implemented_interfaces(): array
    {

        return [ServiceProviderInterface::class, BootableProviderInterface::class];
    }

    /**
     * @return array
     */
    protected function registered_services(): array
    {

        return [
            'Settings.SettingsRepository' => SettingsRepository::class,
            'Settings.Page'               => SettingsPage::class
        ];
    }
}