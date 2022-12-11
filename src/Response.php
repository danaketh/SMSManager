<?php

declare(strict_types=1);

namespace SimPod\SmsManager;

class Response
{
    private const STATUS_OK = 'OK';

    protected bool $isOk;

    /** @var ResponseRequest[] */
    protected array $responseRequests = [];

    public function __construct(protected int $id, protected string $type)
    {
        $this->isOk = $type === self::STATUS_OK;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function addResponseRequest(ResponseRequest $responseRequest): void
    {
        $this->responseRequests[] = $responseRequest;
    }

    /** @return ResponseRequest[] */
    public function getResponseRequests(): array
    {
        return $this->responseRequests;
    }
}
