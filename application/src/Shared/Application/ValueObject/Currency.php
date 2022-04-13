<?php

declare(strict_types=1);

namespace App\Shared\Application\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class Currency
{
    public const EURO = 'EUR';
    public const DOLAR = 'USD';

    private const ALLOWED_STATUSES = [self::EURO, self::DOLAR];

    private string $status;

    /** @throws AssertionFailedException */
    private function __construct(string $status)
    {
        $this->setCurrency($status);
    }

    /** @throws AssertionFailedException */
    public static function from(string $status): self
    {
        return new self($status);
    }

    public function value(): string
    {
        return $this->status;
    }

    /** @throws AssertionFailedException */
    private function setCurrency(string $status): void
    {
        Assertion::choice($status, self::ALLOWED_STATUSES);

        $this->status = $status;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
