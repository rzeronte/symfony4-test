<?php

declare(strict_types=1);

namespace App\Shared\Application\ValueObject;

class FloatNotNullable
{
    protected float $value;

    protected function __construct(float $value)
    {
        $this->setValue($value);
    }

    final public function value(): float
    {
        return $this->value;
    }

    private function setValue(float $value): void
    {
        $this->value = $value;
    }
}
