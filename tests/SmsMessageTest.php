<?php

declare(strict_types=1);

namespace SimPod\SmsManager\Tests;

use PHPUnit\Framework\TestCase;
use SimPod\SmsManager\RequestType;
use SimPod\SmsManager\SmsMessage;

final class SmsMessageTest extends TestCase
{
    /** @dataProvider dataProviderConstruct */
    public function testConstruct(RequestType $expectedRequestType, RequestType|null $requestType): void
    {
        $message    = 'Anchovy essence soup is just not the same without anise and bitter ground pickles.';
        $recipients = ['+420777888999'];
        $sender     = 'sender';
        $customId   = 1;
        $smsMessage = new SmsMessage($message, $recipients, $requestType, $sender, $customId);

        self::assertSame($message, $smsMessage->getMessage());
        self::assertSame($recipients, $smsMessage->getRecipients());
        self::assertSame($expectedRequestType->getValue(), $smsMessage->getRequestType()->getValue());
        self::assertSame($sender, $smsMessage->getSender());
        self::assertSame($customId, $smsMessage->getCustomId());
    }

    /** @return mixed[][] */
    public function dataProviderConstruct(): array
    {
        return [
            [RequestType::getRequestTypeHigh(), null],
            [RequestType::getRequestTypeLow(), RequestType::getRequestTypeLow()],
        ];
    }
}
