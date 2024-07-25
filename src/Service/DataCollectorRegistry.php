<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Service;

use Inpsyde\GoogleTagManager\DataLayer\DataCollector;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecification;

class DataCollectorRegistry
{
    /**
     * @var DataCollector[]
     */
    protected array $collectors = [];

    public static function new(): DataCollectorRegistry
    {
        return new self();
    }

    /**
     * RestEndpointRegistry constructor.
     */
    private function __construct()
    {
    }

    public function register(DataCollector $collector): void
    {
        $this->collectors[$collector->id()] = $collector;
    }

    public function all(): array
    {
        return $this->collectors;
    }
}
