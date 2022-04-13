<?php

declare(strict_types=1);

namespace App\Shared\Application\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;
use JsonSerializable;
use ReflectionClass;

class Uuid implements JsonSerializable
{
    protected string $value;

    /**
     * @throws AssertionFailedException
     */
    protected function __construct(string $value)
    {
        $this->setValue($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value();
    }

    /**
     * @throws AssertionFailedException
     */
    private function setValue(string $value): void
    {
        Assertion::uuid($value, sprintf('%s must be a valid UUID.', (new ReflectionClass($this))->getShortName()));

        $this->value = $value;
    }
}
