<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Rest;

use WP_REST_Request;
use WP_REST_Response;

/**
 * @phpstan-type Route array{
 *     label: string,
 *     methods: string[],
 *     callback: callable(WP_REST_Request): WP_REST_Response,
 *     permission_callback: callable(): bool,
 *     entityName?: string,
 *     entityBaseUrl?: string,
 * }
 */
interface RestEndpoint
{
    /**
     * @return array<string, Route[]>
     */
    public function routes(): array;

    /**
     * The Endpoint base name.
     *
     * @return string
     */
    public static function base(): string;
}
