<?php

declare(strict_types=1);

namespace SimPod\SmsManager;

class ResponseRequest
{
    /** @var string[] */
    protected array $numbers = [];

    public function __construct(
        protected int $requestId,
        protected int $customId,
        protected int $smsCount,
        protected float $smsPrice,
    ) {
    }

    public function getRequestId(): int
    {
        return $this->requestId;
    }

    public function getCustomId(): int
    {
        return $this->customId;
    }

    public function addNumber(string $number): void
    {
        $this->numbers[] = $number;
    }

    /** @return string[] */
    public function getNumbers(): array
    {
        return $this->numbers;
    }

    public function getSmsCount(): int
    {
        return $this->smsCount;
    }

    public function getSmsPrice(): float
    {
        return $this->smsPrice;
    }
}
