<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\UpdateCategory;

use App\Application\Command\CreateCategory\CreateCategoryCommand;
use App\Application\Command\CreateCategory\CreateCategoryCommandHandler;
use App\Application\Command\UpdateCategory\UpdateCategoryCommand;
use App\Application\Command\UpdateCategory\UpdateCategoryCommandHandler;
use App\Domain\Category\Exception\CategoryAlreadyExistsException;
use App\Domain\Category\Exception\CategoryNotFoundException;
use App\Domain\Category\ValueObject\CategoryId;
use App\Infrastructure\Persistence\InMemory\InMemoryCategoryRepository;
use App\Shared\Exception\DomainException;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class UpdateCategoryCommandHandlerTest extends TestCase
{
    private InMemoryCategoryRepository $categoryRepository;

    private const UUID_TEST_FOR_UPDATE = '8382d57f-68c8-4e72-b2f2-6f1f782aeb59';

    /**
     * @throws AssertionFailedException
     */
    public function testUpdateCategoryWithInvalidRequestMustLaunchException()
    {
        $this->expectException(AssertionFailedException::class);
        $command = new UpdateCategoryCommand('', '', '');
        (new UpdateCategoryCommandHandler($this->categoryRepository))($command);

        $this->expectException(AssertionFailedException::class);
        $command = new UpdateCategoryCommand('bad-uuid', '', '');
        (new UpdateCategoryCommandHandler($this->categoryRepository))($command);

        $this->expectException(AssertionFailedException::class);
        $command = new UpdateCategoryCommand(self::UUID_TEST_FOR_UPDATE, '', '');
        (new UpdateCategoryCommandHandler($this->categoryRepository))($command);

        $this->expectException(AssertionFailedException::class);
        $command = new UpdateCategoryCommand(self::UUID_TEST_FOR_UPDATE, 'name updated', '');
        (new UpdateCategoryCommandHandler($this->categoryRepository))($command);

        $this->expectException(AssertionFailedException::class);
        $command = new UpdateCategoryCommand(self::UUID_TEST_FOR_UPDATE, '', 'description updated');
        (new UpdateCategoryCommandHandler($this->categoryRepository))($command);
    }

    /**
     * @throws AssertionFailedException
     */
    public function testUpdateCategoryWithNotExistUUIDMustLaunchException()
    {
        $this->expectException(CategoryNotFoundException::class);
        $command = new UpdateCategoryCommand('35bf7bfa-5967-466e-9908-0fa3d432304d', '', '');
        (new UpdateCategoryCommandHandler($this->categoryRepository))($command);
    }

    /**
     * @throws AssertionFailedException
     */
    public function testUpdateCategoryMustUpdateRecord()
    {
        $command = new UpdateCategoryCommand(self::UUID_TEST_FOR_UPDATE, 'name updated', 'description updated');
        (new UpdateCategoryCommandHandler($this->categoryRepository))($command);

        $category = $this->categoryRepository->ofId(CategoryId::from(self::UUID_TEST_FOR_UPDATE));

        $this->assertEquals('name updated', $category->getName());
        $this->assertEquals('description updated', $category->getDescription());
    }

    /**
     * @throws AssertionFailedException
     * @throws CategoryAlreadyExistsException
     * @throws DomainException
     */
    protected function setUp(): void
    {
        $this->categoryRepository = new InMemoryCategoryRepository();
        $command = new CreateCategoryCommand(self::UUID_TEST_FOR_UPDATE, 'name', 'description');
        (new CreateCategoryCommandHandler($this->categoryRepository))($command);
    }
}
