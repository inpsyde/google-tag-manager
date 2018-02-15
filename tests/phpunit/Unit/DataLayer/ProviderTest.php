<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Unit\DataLayer;

use Inpsyde\GoogleTagManager\Core\BootableProviderInterface;
use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\DataLayer\Provider;
use Inpsyde\GoogleTagManager\DataLayer\Site\SiteInfoDataCollector;
use Inpsyde\GoogleTagManager\DataLayer\User\UserDataCollector;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\GoogleTagManager\Tests\Unit\AbstractProviderTestCase;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ProviderTest extends AbstractProviderTestCase
{

    /**
     * @return ServiceProviderInterface
     */
    protected function get_testee(): ServiceProviderInterface
    {

        return new Provider();
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
            'DataLayer'                            => DataLayer::class,
            'DataLayer.User.UserDataCollector'     => UserDataCollector::class,
            'DataLayer.Site.SiteInfoDataCollector' => SiteInfoDataCollector::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function mock_dependencies(Container $container)
    {

        $settings = \Mockery::mock(SettingsRepository::class);
        $settings->shouldReceive('option')
            ->andReturn([]);

        $container[ 'Settings.SettingsRepository' ] = $settings;
    }
}