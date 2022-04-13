<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Category\ValueObject\CategoryId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class CategoryIdType extends StringType
{
    private const FIELD_ID = 'id';

    public function getName(): string
    {
        return self::FIELD_ID;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof CategoryId) {
            return $value->value();
        }

        if (\is_string($value)) {
            return $value;
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?object
    {
        if (null === $value) {
            return null;
        }

        $className = $this->getClassName();

        return $className::from($value);
    }

    public function getClassName(): string
    {
        return CategoryId::class;
    }
}
