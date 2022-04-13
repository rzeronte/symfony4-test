<?php

declare(strict_types=1);

namespace App\Application\Command\UpdateCategory;

use App\Domain\Category\Repository\CategoryRepository;
use App\Domain\Category\ValueObject\CategoryDescription;
use App\Domain\Category\ValueObject\CategoryId;
use App\Domain\Category\ValueObject\CategoryName;
use Assert\AssertionFailedException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateCategoryCommandHandler implements MessageHandlerInterface
{
    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws AssertionFailedException
     */
    public function __invoke(UpdateCategoryCommand $command)
    {
        $category = $this->repository->ofIdOrFail(CategoryId::from($command->getId()));

        $category->update(
            CategoryName::from($command->getName()),
            CategoryDescription::from($command->getDescription())
        );

        $this->repository->save($category);
    }
}
