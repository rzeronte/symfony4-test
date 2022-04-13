<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Product\ValueObject\ProductName;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class ProductNameType extends StringType
{
    private const FIELD_ID = 'name';

    /** @throws AssertionFailedException */
    public function convertToPHPValue($value, AbstractPlatform $platform): ProductName
    {
        return ProductName::from((string) $value);
    }

    public function getName(): string
    {
        return self::FIELD_ID;
    }
}
