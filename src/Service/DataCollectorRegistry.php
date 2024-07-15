<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Service;

use Inpsyde\GoogleTagManager\DataLayer\DataCollectorInterface;
use Inpsyde\GoogleTagManager\Settings\SettingsSpecAwareInterface;

class DataCollectorRegistry
{
    /**
     * @var DataCollectorInterface[]
     */
    protected array $collectors = [];

    public function register(DataCollectorInterface $collector): void
    {
        $this->collectors[$collector->id()] = $collector;
    }

    public function all(): array
    {
        return $this->collectors;
    }

    public function allFormFields(): array
    {
        $fields = [];
        foreach ($this->all() as $collector) {
            if (!$collector instanceof SettingsSpecAwareInterface) {
                continue;
            }
            $fields[] = [
                'label' => $collector->name(),
                'description' => $collector->description(),
                'attributes' => [
                    'name' => $collector->id(),
                    'type' => 'collection',
                ],
                'elements' => $collector->settingsSpec(),
            ];
        }

        return $fields;
    }
}
