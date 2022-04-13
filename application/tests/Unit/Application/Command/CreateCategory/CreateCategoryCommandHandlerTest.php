<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\CreateCategory;

use App\Application\Command\CreateCategory\CreateCategoryCommand;
use App\Application\Command\CreateCategory\CreateCategoryCommandHandler;
use App\Domain\Category\Exception\CategoryAlreadyExistsException;
use App\Infrastructure\Persistence\InMemory\InMemoryCategoryRepository;
use App\Shared\Exception\DomainException;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class CreateCategoryCommandHandlerTest extends TestCase
{
    private InMemoryCategoryRepository $categoryRepository;

    private const VALID_UUID = 'd1de8bfa-5a55-468b-8946-056ffacb2346';

    /**
     * @throws AssertionFailedException
     * @throws CategoryAlreadyExistsException
     * @throws DomainException
     */
    public function testCreateCategoryWithInvalidRequestBody()
    {
        $this->expectException(AssertionFailedException::class);
        $command = new CreateCategoryCommand('', '', '');
        (new CreateCategoryCommandHandler($this->categoryRepository))($command);

        $this->expectException(AssertionFailedException::class);
        $command = new CreateCategoryCommand('', 'name', 'description');
        (new CreateCategoryCommandHandler($this->categoryRepository))($command);

        $this->expectException(AssertionFailedException::class);
        $command = new CreateCategoryCommand('bad-uuid', 'name', 'description');
        (new CreateCategoryCommandHandler($this->categoryRepository))($command);

        $this->expectException(AssertionFailedException::class);
        $command = new CreateCategoryCommand(self::VALID_UUID, '', 'description');
        (new CreateCategoryCommandHandler($this->categoryRepository))($command);

        $this->expectException(AssertionFailedException::class);
        $command = new CreateCategoryCommand(self::VALID_UUID, 'name', '');
        (new CreateCategoryCommandHandler($this->categoryRepository))($command);
    }

    /**
     * @throws AssertionFailedException
     * @throws CategoryAlreadyExistsException
     * @throws DomainException
     */
    public function testCreateCategoryWithValidRequestMustInsertRecord()
    {
        $categoriesInitial = $this->categoryRepository->count();

        $command = new CreateCategoryCommand(self::VALID_UUID, 'name', 'description');
        (new CreateCategoryCommandHandler($this->categoryRepository))($command);

        $this->assertEquals($categoriesInitial + 1, $this->categoryRepository->count());
    }

    /**
     * @throws AssertionFailedException
     * @throws CategoryAlreadyExistsException
     * @throws DomainException
     */
    public function testCreateCategoryThatAlreadyExistsMustThrowException()
    {
        $this->expectException(CategoryAlreadyExistsException::class);

        $command = new CreateCategoryCommand(self::VALID_UUID, 'name', 'description');
        (new CreateCategoryCommandHandler($this->categoryRepository))($command);

        $command = new CreateCategoryCommand(self::VALID_UUID, 'name', 'description');
        (new CreateCategoryCommandHandler($this->categoryRepository))($command);
    }

    protected function setUp(): void
    {
        $this->categoryRepository = new InMemoryCategoryRepository();
    }
}
