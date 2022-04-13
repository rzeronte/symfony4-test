<?php

declare(strict_types=1);

namespace App\Application\Command\CreateCategory;

use App\Domain\Category\Category;
use App\Domain\Category\Exception\CategoryAlreadyExistsException;
use App\Domain\Category\Repository\CategoryRepository;
use App\Domain\Category\ValueObject\CategoryDescription;
use App\Domain\Category\ValueObject\CategoryId;
use App\Domain\Category\ValueObject\CategoryName;
use App\Shared\Exception\DomainException;
use Assert\AssertionFailedException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateCategoryCommandHandler implements MessageHandlerInterface
{
    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws AssertionFailedException
     * @throws CategoryAlreadyExistsException
     * @throws DomainException
     */
    public function __invoke(CreateCategoryCommand $command)
    {
        $categoryId = CategoryId::from($command->getId());

        $this->ensureThatCategoryNotExists(CategoryId::from($command->getId()));

        $category = Category::create(
            $categoryId,
            CategoryName::from($command->getName()),
            CategoryDescription::from($command->getDescription())
        );

        $this->repository->save($category);
    }

    /**
     * @throws CategoryAlreadyExistsException
     * @throws DomainException
     */
    private function ensureThatCategoryNotExists(CategoryId $categoryId)
    {
        if (null !== $this->repository->ofId($categoryId)) {
            throw CategoryAlreadyExistsException::create($categoryId->value());
        }
    }
}
