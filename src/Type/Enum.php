<?php

declare(strict_types=1);

namespace SimPod\SmsManager\Type;

/** @template TEntity as object */
abstract class Enum
{
    final public function __construct(private int|string|float|bool $value)
    {
    }

    public function getValue(): int|string|float|bool
    {
        return $this->value;
    }

    /** @return static TEntity */
    public static function get(int|string|float|bool $value): object
    {
        return new static($value);
    }
}
