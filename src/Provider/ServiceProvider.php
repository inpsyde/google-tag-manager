<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Provider;

use Inpsyde\GoogleTagManager\Rest\SettingsPageEndpoint;
use Inpsyde\GoogleTagManager\Service\DataCollectorRegistry;
use Inpsyde\GoogleTagManager\Service\RestEndpointRegistry;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;

class ServiceProvider implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            DataCollectorRegistry::class => [DataCollectorRegistry::class, 'new'],
            RestEndpointRegistry::class => static function (ContainerInterface $container): RestEndpointRegistry {
                $registry = RestEndpointRegistry::new();
                $registry->addEndpoint($container->get(SettingsPageEndpoint::class));

                return $registry;
            },
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action(
            'rest_api_init',
            static function () use ($container) {
                $registry = $container->get(RestEndpointRegistry::class);

                $registry->register();
            }
        );

        return true;
    }
}
