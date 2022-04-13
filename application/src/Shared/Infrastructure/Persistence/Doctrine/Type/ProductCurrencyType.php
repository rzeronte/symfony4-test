<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Application\ValueObject\Currency;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class ProductCurrencyType extends StringType
{
    private const FIELD_ID = 'currency';

    /**
     * @param $value
     * @throws AssertionFailedException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): Currency
    {
        return Currency::from($value);
    }

    public function getName(): string
    {
        return self::FIELD_ID;
    }
}
