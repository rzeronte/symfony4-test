<?php

declare(strict_types=1);

namespace App\Domain\Category\Repository;

use App\Domain\Category\Category;
use App\Domain\Category\ValueObject\CategoryId;

interface CategoryRepository
{
    public function save(Category $category): void;

    public function delete(Category $category): void;

    public function ofIdOrFail(CategoryId $categoryId): Category;

    public function ofId(CategoryId $categoryId): ?Category;

    public function count(): int;
}
