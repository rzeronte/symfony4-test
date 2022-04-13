<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Query\RetrieveProducts;

use App\Application\Query\RetrieveProducts\RetrieveProductsQuery;
use App\Application\Query\RetrieveProducts\RetrieveProductsQueryHandler;
use App\Domain\Category\Category;
use App\Domain\Category\ValueObject\CategoryDescription;
use App\Domain\Category\ValueObject\CategoryId;
use App\Domain\Category\ValueObject\CategoryName;
use App\Domain\Product\Product;
use App\Domain\Product\ValueObject\ProductId;
use App\Domain\Product\ValueObject\ProductName;
use App\Domain\Product\ValueObject\ProductPrice;
use App\Infrastructure\Persistence\InMemory\InMemoryProductRepository;
use App\Shared\Application\ValueObject\Currency;
use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RetrieveProductsQueryHandlerTest extends TestCase
{
    private InMemoryProductRepository $productsRepository;

    private Category $category;

    private const UUID_TEST = '7d855cb7-3e01-404b-8e0f-f5bdb6cfc70b';

    public function testRetrieveProductsWithEmptyRepository()
    {
        $query = new RetrieveProductsQuery(null, 1, 1);
        $handler = new RetrieveProductsQueryHandler($this->productsRepository);
        $paginator = $handler->__invoke($query);

        $expectedSerialize = [
            'products' => [
            ],
            'meta' => [
                'currentPage' => 1,
                'lastPage' => 0,
                'size' => 1,
                'total' => 0,
            ],
        ];
        $this->assertEquals(json_encode($expectedSerialize), json_encode($paginator->jsonSerialize()));
    }

    public function testRetrieveProductsWithBadArguments()
    {
        $this->expectException(InvalidArgumentException::class);
        $query = new RetrieveProductsQuery(null, 0, 1);
        $handler = new RetrieveProductsQueryHandler($this->productsRepository);
        $handler->__invoke($query);

        $this->expectException(InvalidArgumentException::class);
        $query = new RetrieveProductsQuery(null, 1, 0);
        $handler = new RetrieveProductsQueryHandler($this->productsRepository);
        $handler->__invoke($query);
    }

    /**
     * @throws AssertionFailedException
     */
    public function testRetrieveProductsForPaginatorMutants()
    {
        $this->productsRepository->save(
            Product::create(
                ProductId::from('d5c509a1-3e74-4daf-9626-55e0c2665957'),
                ProductName::from('Name'),
                null,
                ProductPrice::from(1),
                Currency::from('USD'),
                false
            )
        );

        $this->productsRepository->save(
            Product::create(
                ProductId::from('ab4b18e9-0596-4e31-8747-253db434029e'),
                ProductName::from('Name'),
                null,
                ProductPrice::from(1),
                Currency::from('USD'),
                false
            )
        );

        $this->productsRepository->save(
            Product::create(
                ProductId::from('34104ca7-e89d-42fc-a4dc-94aa44253feb'),
                ProductName::from('Name'),
                null,
                ProductPrice::from(1),
                Currency::from('USD'),
                false
            )
        );

        $this->productsRepository->save(
            Product::create(
                ProductId::from('26cbc61c-aed1-4c2c-b0fb-50f5cf78ea46'),
                ProductName::from('Name'),
                null,
                ProductPrice::from(1),
                Currency::from('USD'),
                false
            )
        );

        $query = new RetrieveProductsQuery(null, 2, 1);
        $handler = new RetrieveProductsQueryHandler($this->productsRepository);
        $paginator = $handler->__invoke($query);

        $expectedSerialize = [
            'currentPage' => 2,
            'lastPage' => 4,
            'size' => 1,
            'total' => 4,
        ];
        $this->assertEquals(json_encode($expectedSerialize), json_encode($paginator->jsonSerialize()['meta']));

        $query = new RetrieveProductsQuery(null, 1, 3);
        $handler = new RetrieveProductsQueryHandler($this->productsRepository);
        $paginator = $handler->__invoke($query);

        $expectedSerialize = [
            'currentPage' => 1,
            'lastPage' => 2,
            'size' => 3,
            'total' => 4,
        ];
        $this->assertEquals(json_encode($expectedSerialize), json_encode($paginator->jsonSerialize()['meta']));
    }

    /**
     * @throws AssertionFailedException
     */
    public function testRetrieveProductsWithRecordsRepository()
    {
        $this->productsRepository->save(
            Product::create(
                ProductId::from('d5c509a1-3e74-4daf-9626-55e0c2665957'),
                ProductName::from('Name'),
                $this->category,
                ProductPrice::from(1),
                Currency::from('USD'),
                false
            )
        );

        $query = new RetrieveProductsQuery(null, 1, 1);
        $handler = new RetrieveProductsQueryHandler($this->productsRepository);
        $paginator = $handler->__invoke($query);

        $expectedSerialize = [
            'products' => [
                [
                    'id' => 'd5c509a1-3e74-4daf-9626-55e0c2665957',
                    'name' => 'Name',
                    'categoryId' => [
                        'id' => '7d855cb7-3e01-404b-8e0f-f5bdb6cfc70b',
                        'name' => 'name',
                        'description' => 'description',
                    ],
                    'price' => 1,
                    'currency' => 'USD',
                    'featured' => false,
                ],
            ],
            'meta' => [
                'currentPage' => 1,
                'lastPage' => 1,
                'size' => 1,
                'total' => 1,
            ],
        ];
        $this->assertEquals(json_encode($expectedSerialize), json_encode($paginator->jsonSerialize()));
    }

    /**
     * @throws AssertionFailedException
     */
    protected function setUp(): void
    {
        $this->productsRepository = new InMemoryProductRepository();
        $this->category = Category::create(
            CategoryId::from(self::UUID_TEST),
            CategoryName::from('name'),
            CategoryDescription::from('description')
        );
    }
}
