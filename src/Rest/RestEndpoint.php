<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Rest;

interface RestEndpoint
{
    /**
     * @return array<string, array>
     */
    public function routes(): array;

    /**
     * The Endpoint base name.
     *
     * @return string
     */
    public static function base(): string;
}
