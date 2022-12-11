<?php

declare(strict_types=1);

namespace SimPod\SmsManager;

use SimPod\SmsManager\Exception\NoRecipientsProvided;

use function count;

class SmsMessage
{
    protected RequestType $requestType;

    /** @param string[] $recipients */
    public function __construct(
        protected string $message,
        protected array $recipients = [],
        RequestType|null $requestTypeHigh = null,
        protected string|null $sender = null,
        protected int|null $customId = null,
    ) {
        if (count($recipients) === 0) {
            throw new NoRecipientsProvided();
        }

        if ($requestTypeHigh === null) {
            $requestTypeHigh = RequestType::getRequestTypeHigh();
        }

        $this->requestType = $requestTypeHigh;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getRequestType(): RequestType
    {
        return $this->requestType;
    }

    /** @return string[] */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function getSender(): string|null
    {
        return $this->sender;
    }

    public function getCustomId(): int|null
    {
        return $this->customId;
    }
}
