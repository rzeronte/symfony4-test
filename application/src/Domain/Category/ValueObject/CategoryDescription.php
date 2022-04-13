<?php

declare(strict_types=1);

namespace App\Domain\Category\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Stringable;

final class CategoryDescription implements Stringable
{
    private string $value;

    /** @throws AssertionFailedException */
    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    /** @throws AssertionFailedException */
    public static function from(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    /** @throws AssertionFailedException */
    private function setValue(string $value): void
    {
        Assertion::notBlank($value, 'Category description cannot be empty');
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value();
    }
}
