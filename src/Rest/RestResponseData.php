<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Rest;

class RestResponseData implements \JsonSerializable
{
    /**
     * @param string $message
     * @param bool $successful
     * @param array<string, mixed> $data
     *
     * @return RestResponseData
     */
    public static function new(string $message, bool $successful = false, array $data = []): RestResponseData
    {
        return new self($message, $successful, $data);
    }

    /**
     * @param \Throwable $throwable
     *
     * @return RestResponseData
     */
    public static function fromThrowable(\Throwable $throwable): RestResponseData
    {
        return new self($throwable->getMessage(), false, []);
    }

    /**
     * @see RestResponseData::new()
     * @see RestResponseData::fromThrowable()
     * @phpstan-ignore-next-line missingType.iterableValue
     */
    protected function __construct(
        protected string $message,
        protected bool $successful,
        protected array $data,
    ) {
    }

    /**
     * @return array{message: string, success: bool, data: array<string, mixed>}
     */
    public function jsonSerialize(): array
    {
        return [
            'message' => $this->message,
            'success' => $this->successful,
            'data' => $this->data,
        ];
    }
}
