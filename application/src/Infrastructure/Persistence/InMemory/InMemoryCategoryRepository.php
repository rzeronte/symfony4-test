<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\InMemory;

use App\Domain\Category\Category;
use App\Domain\Category\Exception\CategoryNotFoundException;
use App\Domain\Category\Repository\CategoryRepository;
use App\Domain\Category\ValueObject\CategoryId;
use App\Shared\Exception\DomainException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class InMemoryCategoryRepository implements CategoryRepository
{
    /**
     * @var Collection<string, Category>
     */
    private Collection $categories;

    public function __construct(array $categories = [])
    {
        $this->categories = new ArrayCollection([]);

        foreach ($categories as $analyticalTest) {
            $this->save($analyticalTest);
        }
    }

    public function save(Category $category): void
    {
        $id = $category->getId()->value();
        $this->categories->set($id, $category);
    }

    public function delete(Category $category): void
    {
        $this->categories->remove($category->getId()->value());
    }

    /**
     * @throws DomainException
     */
    public function ofIdOrFail(CategoryId $categoryId): Category
    {
        $category = $this->categories->get($categoryId->value());

        if (!($category instanceof Category)) {
            throw CategoryNotFoundException::create($categoryId->value());
        }

        return clone $category;
    }

    public function ofId(CategoryId $categoryId): ?Category
    {
        return $this->categories->get($categoryId->value());
    }

    public function count(): int
    {
        return $this->categories->count();
    }
}
