<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Category\ValueObject\CategoryDescription;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class CategoryDescriptionType extends StringType
{
    private const FIELD_ID = 'description';

    /** @throws AssertionFailedException */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?CategoryDescription
    {
        if (!is_scalar($value)) {
            return null;
        }

        return CategoryDescription::from((string) $value);
    }

    public function getName(): string
    {
        return self::FIELD_ID;
    }
}
