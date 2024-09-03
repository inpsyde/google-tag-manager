<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Provider;

use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Rest\SettingsPageEndpoint;
use Inpsyde\GoogleTagManager\Service\DataCollectorRegistry;
use Inpsyde\GoogleTagManager\Settings\SettingsRepository;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;

class RestProvider implements ServiceModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            SettingsPageEndpoint::class => static function (ContainerInterface $container): SettingsPageEndpoint {
                $dataLayer = $container->get(DataLayer::class);
                $registry = $container->get(DataCollectorRegistry::class);
                $repository = $container->get(SettingsRepository::class);

                return SettingsPageEndpoint::new($dataLayer, $registry, $repository);
            },
        ];
    }
}
