<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Category\ValueObject\CategoryId;
use App\Domain\Product\ValueObject\ProductId;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class ProductIdType extends StringType
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
     * @param $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return null === $value || \is_string($value)
            ? $value
            : (string) $value->value();
    }

    /**
     * {@inheritdoc}
     * @throws AssertionFailedException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): object
    {
        return ProductId::from($value);
    }

    public function getClassName(): string
    {
        return CategoryId::class;
    }
}
