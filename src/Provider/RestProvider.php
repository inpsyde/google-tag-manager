<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Provider;

use Inpsyde\GoogleTagManager\DataLayer\DataLayer;
use Inpsyde\GoogleTagManager\Rest\DataLayerEndpoint;
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
            DataLayerEndpoint::class => static function (ContainerInterface $container): DataLayerEndpoint {
                $dataLayer = $container->get(DataLayer::class);
                $registry = $container->get(DataCollectorRegistry::class);
                $repository = $container->get(SettingsRepository::class);

                return DataLayerEndpoint::new($dataLayer, $registry, $repository);
            },
        ];
    }
}
