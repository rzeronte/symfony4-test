<?php

declare(strict_types=1);

namespace App\Domain\Product\ValueObject;

use App\Shared\Application\ValueObject\FloatNotNullable;
use Assert\Assertion;
use Assert\AssertionFailedException;

final class ProductPrice extends FloatNotNullable
{
    /** @throws AssertionFailedException */
    public static function from(float $value): self
    {
        Assertion::greaterThan($value, 0);

        return new self($value);
    }
}
