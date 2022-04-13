<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\DeleteCategory;

use App\Application\Command\CreateCategory\CreateCategoryCommand;
use App\Application\Command\CreateCategory\CreateCategoryCommandHandler;
use App\Application\Command\DeleteCategory\DeleteCategoryCommand;
use App\Application\Command\DeleteCategory\DeleteCategoryCommandHandler;
use App\Domain\Category\Exception\CategoryAlreadyExistsException;
use App\Domain\Category\Exception\CategoryNotFoundException;
use App\Infrastructure\Persistence\InMemory\InMemoryCategoryRepository;
use App\Shared\Exception\DomainException;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class DeleteCategoryCommandHandlerTest extends TestCase
{
    private InMemoryCategoryRepository $categoryRepository;

    private const UUID_NOT_EXIST = 'dc7bf464-f96e-4129-803a-4aa90431eaf8';
    private const UUID_TEST_FOR_DELETE = '8382d57f-68c8-4e72-b2f2-6f1f782aeb59';

    /**
     * @throws AssertionFailedException
     */
    public function testDeleteCategoryWithInvalidUUIDMustLaunchException()
    {
        $this->expectException(AssertionFailedException::class);
        $command = new DeleteCategoryCommand('');
        (new DeleteCategoryCommandHandler($this->categoryRepository))($command);
    }

    /**
     * @throws AssertionFailedException
     */
    public function testDeleteCategoryThatNotExistMustLaunchException()
    {
        $this->expectException(CategoryNotFoundException::class);
        $command = new DeleteCategoryCommand(self::UUID_NOT_EXIST);
        (new DeleteCategoryCommandHandler($this->categoryRepository))($command);
    }

    /**
     * @throws AssertionFailedException
     * @throws CategoryAlreadyExistsException
     * @throws DomainException
     */
    public function testDeleteCategoryMustEraseRecord()
    {
        $command = new CreateCategoryCommand(self::UUID_TEST_FOR_DELETE, 'name', 'description');
        (new CreateCategoryCommandHandler($this->categoryRepository))($command);

        $command = new DeleteCategoryCommand(self::UUID_TEST_FOR_DELETE);
        (new DeleteCategoryCommandHandler($this->categoryRepository))($command);

        $this->assertEquals(0, $this->categoryRepository->count());
    }

    protected function setUp(): void
    {
        $this->categoryRepository = new InMemoryCategoryRepository();
    }
}
