<?php

declare(strict_types=1);

namespace App\Application\Command\DeleteCategory;

use App\Domain\Category\Repository\CategoryRepository;
use App\Domain\Category\ValueObject\CategoryId;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class DeleteCategoryCommandHandler implements MessageHandlerInterface
{
    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /** @throws \Assert\AssertionFailedException */
    public function __invoke(DeleteCategoryCommand $command): void
    {
        $this->repository->delete($this->repository->ofIdOrFail(CategoryId::from($command->getId())));
    }
}
