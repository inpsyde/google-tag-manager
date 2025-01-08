<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Service;

use Inpsyde\GoogleTagManager\DataLayer\DataCollector;

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

    /**
     * @param DataCollector $collector
     *
     * @return void
     */
    public function register(DataCollector $collector): void
    {
        $this->collectors[$collector->id()] = $collector;
    }

    /**
     * @return DataCollector[]
     */
    public function all(): array
    {
        return $this->collectors;
    }
}
