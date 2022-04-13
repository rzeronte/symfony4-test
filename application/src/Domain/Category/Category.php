<?php

declare(strict_types=1);

namespace App\Domain\Category;

use App\Domain\Category\ValueObject\CategoryDescription;
use App\Domain\Category\ValueObject\CategoryId;
use App\Domain\Category\ValueObject\CategoryName;

final class Category implements \JsonSerializable
{
    private CategoryId $id;
    private CategoryName $name;
    private CategoryDescription $description;

    private function __construct(CategoryId $id, CategoryName $name, CategoryDescription $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public static function create(CategoryId $id, CategoryName $name, CategoryDescription $description): self
    {
        return new self($id, $name, $description);
    }

    public function getId(): CategoryId
    {
        return $this->id;
    }

    public function getName(): CategoryName
    {
        return $this->name;
    }

    public function getDescription(): CategoryDescription
    {
        return $this->description;
    }

    public function update(CategoryName $name, CategoryDescription $description): void
    {
        $this->name = $name;
        $this->description = $description;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName()->value(),
            'description' => $this->getDescription()->value(),
        ];
    }
}
