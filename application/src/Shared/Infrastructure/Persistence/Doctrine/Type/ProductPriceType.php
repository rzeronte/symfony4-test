<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Product\ValueObject\ProductPrice;
use Assert\AssertionFailedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class ProductPriceType extends StringType
{
    private const FIELD_ID = 'price';

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
    public function convertToDatabaseValue($value, AbstractPlatform $platform): float
    {
        return $value->value();
    }

    /**
     * {@inheritdoc}
     * @throws AssertionFailedException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): object
    {
        return ProductPrice::from((float) $value);
    }

    public function getClassName(): string
    {
        return ProductPrice::class;
    }
}
