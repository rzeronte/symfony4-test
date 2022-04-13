<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Category\Category;
use App\Domain\Category\Exception\CategoryNotFoundException;
use App\Domain\Category\Repository\CategoryRepository;
use App\Domain\Category\ValueObject\CategoryId;
use App\Shared\Exception\DomainException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

final class DoctrineCategoryRepository implements CategoryRepository
{
    private ObjectRepository $repository;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Category::class);
    }

    public function save(Category $category): void
    {
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }

    public function delete(Category $category): void
    {
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }

    /** @throws DomainException */
    public function ofIdOrFail(CategoryId $categoryId): Category
    {
        $category = $this->repository->find($categoryId->value());

        if (!($category instanceof Category)) {
            throw CategoryNotFoundException::create(
                $categoryId->value()
            );
        }

        return $category;
    }

    public function ofId(CategoryId $categoryId): ?Category
    {
        return $this->repository->find($categoryId->value());
    }

    public function count(): int
    {
        return $this->repository->count([]);
    }
}
