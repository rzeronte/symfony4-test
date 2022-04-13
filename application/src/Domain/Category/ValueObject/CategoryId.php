<?php

declare(strict_types=1);

namespace App\Domain\Category\ValueObject;

use App\Shared\Application\ValueObject\Uuid;
use Assert\AssertionFailedException;

final class CategoryId extends Uuid implements \Stringable
{
    /** @throws AssertionFailedException */
    public static function from(string $value): self
    {
        return new self($value);
    }

    public function __toString()
    {
        return $this->value;
    }
}
