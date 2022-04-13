<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\CreateProduct;

use App\Application\Command\CreateProduct\CreateProductCommand;
use App\Application\Command\CreateProduct\CreateProductCommandHandler;
use App\Domain\Category\Category;
use App\Domain\Category\ValueObject\CategoryDescription;
use App\Domain\Category\ValueObject\CategoryId;
use App\Domain\Category\ValueObject\CategoryName;
use App\Infrastructure\Persistence\InMemory\InMemoryCategoryRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryProductRepository;
use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CreateProductCommandHandlerTest extends TestCase
{
    private InMemoryProductRepository $productRepository;
    private InMemoryCategoryRepository $categoryRepository;

    private const CATEGORY_ID = 'd1de8bfa-5a55-468b-8946-056ffacb2346';

    /**
     * @throws AssertionFailedException
     */
    public function testCreateProductWithInvalidRequestMustLaunchException()
    {
        $this->expectException(InvalidArgumentException::class);
        $command = new CreateProductCommand('', 'Product name', null, 1, 'EUR', false);
        (new CreateProductCommandHandler($this->productRepository, $this->categoryRepository))($command);

        $this->expectException(InvalidArgumentException::class);
        $command = new CreateProductCommand('265dc2f9-d4be-462b-97bf-2e2d2f371109', '', null, 1, 'EUR', false);
        (new CreateProductCommandHandler($this->productRepository, $this->categoryRepository))($command);

        $this->expectException(InvalidArgumentException::class);
        $command = new CreateProductCommand('265dc2f9-d4be-462b-97bf-2e2d2f371109', 'name', null, 0, 'EUR', false);
        (new CreateProductCommandHandler($this->productRepository, $this->categoryRepository))($command);

        $this->expectException(InvalidArgumentException::class);
        $command = new CreateProductCommand('265dc2f9-d4be-462b-97bf-2e2d2f371109', 'name', null, 1, 'PEPE', false);
        (new CreateProductCommandHandler($this->productRepository, $this->categoryRepository))($command);
    }

    /**
     * @throws AssertionFailedException
     */
    public function testCreateProductWithValidRequestButNoCategoryMustInsertRecord()
    {
        $productsInitial = $this->productRepository->searchCount(null);

        $command = new CreateProductCommand(
            '9db3d1c3-ee68-45cb-8cc7-e5a95366e2a9',
            'Product name',
            null,
            1,
            'EUR',
            false
        );
        (new CreateProductCommandHandler($this->productRepository, $this->categoryRepository))($command);

        $this->assertEquals($productsInitial + 1, $this->productRepository->searchCount(null));
    }

    /**
     * @throws AssertionFailedException
     */
    public function testCreateProductWithValidRequestIncludeCategoryMustInsertRecord()
    {
        $productsInitial = $this->productRepository->searchCount(null);

        $command = new CreateProductCommand(
            '9db3d1c3-ee68-45cb-8cc7-e5a95366e2a9',
            'Product name',
            self::CATEGORY_ID,
            1,
            'EUR',
            false
        );

        (new CreateProductCommandHandler($this->productRepository, $this->categoryRepository))($command);

        $this->assertEquals($productsInitial + 1, $this->productRepository->searchCount(null));
    }

    /**
     * @throws AssertionFailedException
     */
    protected function setUp(): void
    {
        $this->productRepository = new InMemoryProductRepository();
        $this->categoryRepository = new InMemoryCategoryRepository();
        $this->categoryRepository->save(Category::create(
            CategoryId::from(self::CATEGORY_ID),
            CategoryName::from('name'),
            CategoryDescription::from('description')
        ));
    }
}
