<?php

declare(strict_types=1);

namespace SimPod\SmsManager;

use SimPod\SmsManager\Type\Enum;

/** @extends Enum<RequestType> */
final class RequestType extends Enum
{
    public const REQUEST_TYPE_LOW     = 'lowcost';
    public const REQUEST_TYPE_ECONOMY = 'economy';
    public const REQUEST_TYPE_HIGH    = 'high';

    public static function getRequestTypeLow(): self
    {
        return self::get(self::REQUEST_TYPE_LOW);
    }

    public static function getRequestTypeEconomy(): self
    {
        return self::get(self::REQUEST_TYPE_ECONOMY);
    }

    public static function getRequestTypeHigh(): self
    {
        return self::get(self::REQUEST_TYPE_HIGH);
    }

    public function getValue(): string
    {
        return (string) parent::getValue();
    }
}
