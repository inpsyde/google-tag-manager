<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Rest;

class RestResponseData implements \JsonSerializable
{
    private array $data = [
        'success' => false,
    ];

    /**
     * @param string $message
     * @param bool $successful
     * @param array $data
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
        return new self($throwable->getMessage(), false);
    }

    /**
     * @see RestResponseData::new()
     * @see RestResponseData::fromThrowable()
     */
    protected function __construct(string $message, bool $successful = false, array $data = [])
    {
        $this->withMessage($message);
        $successful
            ? $this->makeSuccessful()
            : $this->makeUnsuccessful();
        $this->withData($data);
    }

    public function withMessage(string $message): RestResponseData
    {
        $this->data['message'] = $message;

        return $this;
    }

    public function withData(array $data): RestResponseData
    {
        $this->data['data'] = $data;

        return $this;
    }

    public function makeSuccessful(): RestResponseData
    {
        $this->data['success'] = true;

        return $this;
    }

    public function makeUnsuccessful(): RestResponseData
    {
        $this->data['success'] = false;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return $this->data['data'];
    }
}
