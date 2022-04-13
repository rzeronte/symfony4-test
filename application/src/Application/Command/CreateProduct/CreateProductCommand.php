<?php

declare(strict_types=1);

namespace App\Application\Command\CreateProduct;

final class CreateProductCommand
{
    private string $id;
    private string $name;
    private ?string $categoryId;
    private float $price;
    private string $currency;
    private bool $featured;

    public function __construct(
        string $id,
        string $name,
        ?string $categoryId,
        float $price,
        string $currency,
        bool $featured
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->categoryId = $categoryId;
        $this->price = $price;
        $this->currency = $currency;
        $this->featured = $featured;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategoryId(): ?string
    {
        return $this->categoryId;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function isFeatured(): bool
    {
        return $this->featured;
    }
}
