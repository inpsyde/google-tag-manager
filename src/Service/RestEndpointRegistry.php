<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Service;

use Inpsyde\GoogleTagManager\Rest\RestEndpoint;

class RestEndpointRegistry
{
    public const NAMESPACE = 'inpsyde-google-tag-manager/v1';

    /**
     * @var array<string, RestEndpoint>
     */
    private array $endpoints = [];

    /**
     * @var array<string, array<string, bool>>
     */
    private array $registered = [];

    /**
     * @var array<string, array{label:string, name:string, baseURL:string, kind:string}>
     */
    private array $entities = [];

    protected function __construct()
    {
    }

    /**
     * @return RestEndpointRegistry
     */
    public static function new(): RestEndpointRegistry
    {
        return new self();
    }

    /**
     * @param RestEndpoint $endpoint
     *
     * @return void
     *
     * phpcs:disable Inpsyde.CodeQuality.NestingLevel.High
     */
    public function addEndpoint(RestEndpoint $endpoint): void
    {
        $this->endpoints[$endpoint->base()] = $endpoint;

        foreach ($endpoint->routes() as $route => $args) {
            foreach ($args as $arg) {
                $name = $arg['entityName'] ?? '';
                $baseUrl = $arg['entityBaseUrl'] ?? '';
                if (!$name || !$baseUrl) {
                    continue;
                }
                $this->entities[$name] = [
                    'label' => (string) $arg['label'],
                    'name' => (string) $name,
                    'kind' => self::NAMESPACE,
                    'supportsPagination' => false,
                    'baseURL' => '/' . self::NAMESPACE . rtrim($baseUrl, "/"),
                ];
            }
        }
    }

    /**
     * @return bool
     *
     * phpcs:disable Inpsyde.CodeQuality.NestingLevel.High
     */
    public function register(): bool
    {
        foreach ($this->endpoints as $base => $endpoint) {
            if ($this->registered[$base] ?? false) {
                continue;
            }
            foreach ($endpoint->routes() as $route => $args) {
                $registered = register_rest_route(
                    self::NAMESPACE,
                    $route,
                    $args
                );
                $this->registered[$base][$route] = $registered;
            }
        }

        return true;
    }

    /**
     * @return array<string, array{label:string, name:string, baseURL:string, kind:string}>
     */
    public function entities(): array
    {
        return $this->entities;
    }

    /**
     * @return array<string, array<string, bool>>
     */
    public function registered(): array
    {
        return $this->registered;
    }
}
