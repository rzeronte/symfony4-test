<?php

declare(strict_types=1);

namespace App\Domain\Product\ValueObject;

use App\Shared\Application\ValueObject\Uuid;
use Assert\AssertionFailedException;
use Stringable;

final class ProductId extends Uuid implements Stringable
{
    /** @throws AssertionFailedException */
    public static function from(string $value): self
    {
        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
