<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Category\ValueObject\CategoryName;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class CategoryNameType extends StringType
{
    private const FIELD_ID = 'name';

    /** @throws AssertionFailedException */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?CategoryName
    {
        if (!is_scalar($value)) {
            return null;
        }

        return CategoryName::from((string) $value);
    }

    public function getName(): string
    {
        return self::FIELD_ID;
    }
}
