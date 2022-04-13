<?php

declare(strict_types=1);

namespace App\Domain\Product;

use App\Domain\Category\Category;
use App\Domain\Product\ValueObject\ProductId;
use App\Domain\Product\ValueObject\ProductName;
use App\Domain\Product\ValueObject\ProductPrice;
use App\Shared\Application\ValueObject\Currency;
use JsonSerializable;

final class Product implements JsonSerializable
{
    private ProductId $id;
    private ProductName $name;
    private ?Category $category;
    private ProductPrice $price;
    private Currency $currency;
    private bool $featured;

    public function __construct(
        ProductId $id,
        ProductName $name,
        ?Category $category,
        ProductPrice $price,
        Currency $currency,
        bool $featured
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
        $this->price = $price;
        $this->currency = $currency;
        $this->featured = $featured;
    }

    public static function create(
        ProductId $id,
        ProductName $name,
        ?Category $category,
        ProductPrice $price,
        Currency $currency,
        bool $featured
    ): self {
        return new self($id, $name, $category, $price, $currency, $featured);
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    public function getName(): ProductName
    {
        return $this->name;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getFeatured(): bool
    {
        return $this->featured;
    }

    public function getPrice(): ProductPrice
    {
        return $this->price;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName()->value(),
            'categoryId' => $this->getCategory(),
            'price' => $this->getPrice()->value(),
            'currency' => $this->getCurrency()->value(),
            'featured' => $this->getFeatured(),
        ];
    }
}
