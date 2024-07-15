<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Provider;

use Inpsyde\GoogleTagManager\Service\DataCollectorRegistry;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;

class ServiceProvider implements ServiceModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            DataCollectorRegistry::class => static fn (): dataCollectorRegistry => new DataCollectorRegistry(),
        ];
    }
}
