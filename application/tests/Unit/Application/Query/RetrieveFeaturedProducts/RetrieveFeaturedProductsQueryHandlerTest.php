<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Query\RetrieveFeaturedProducts;

use App\Application\Query\RetrieveFeaturedProducts\RetrieveFeaturedProductsQuery;
use App\Application\Query\RetrieveFeaturedProducts\RetrieveFeaturedProductsQueryHandler;
use App\Domain\Product\Product;
use App\Domain\Product\ValueObject\ProductId;
use App\Domain\Product\ValueObject\ProductName;
use App\Domain\Product\ValueObject\ProductPrice;
use App\Infrastructure\Persistence\InMemory\InMemoryProductRepository;
use App\Shared\Application\ValueObject\Currency;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class RetrieveFeaturedProductsQueryHandlerTest extends TestCase
{
    private InMemoryProductRepository $productsRepository;

    public function testRetrieveFeaturedProductsWithEmptyRepository()
    {
        $query = new RetrieveFeaturedProductsQuery(1, 50);
        $handler = new RetrieveFeaturedProductsQueryHandler($this->productsRepository);
        $paginator = $handler->__invoke($query);

        $expectedSerialize = [
            'products' => [
            ],
            'meta' => [
                'currentPage' => 1,
                'lastPage' => 0,
                'size' => 50,
                'total' => 0,
            ],
        ];
        $this->assertEquals(json_encode($expectedSerialize), json_encode($paginator->jsonSerialize()));
    }

    /**
     * @throws AssertionFailedException
     */
    public function testRetrieveFeaturedProductsForPaginatorMutants()
    {
        $this->productsRepository->save(
            Product::create(
                ProductId::from('d5c509a1-3e74-4daf-9626-55e0c2665957'),
                ProductName::from('Name1'),
                null,
                ProductPrice::from(1),
                Currency::from('USD'),
                false
            )
        );

        $this->productsRepository->save(
            Product::create(
                ProductId::from('ab4b18e9-0596-4e31-8747-253db434029e'),
                ProductName::from('Name2'),
                null,
                ProductPrice::from(1),
                Currency::from('USD'),
                true
            )
        );

        $this->productsRepository->save(
            Product::create(
                ProductId::from('34104ca7-e89d-42fc-a4dc-94aa44253feb'),
                ProductName::from('Name3'),
                null,
                ProductPrice::from(1),
                Currency::from('USD'),
                true
            )
        );

        $query = new RetrieveFeaturedProductsQuery();
        $handler = new RetrieveFeaturedProductsQueryHandler($this->productsRepository);
        $paginator = $handler->__invoke($query);

        $expectedSerialize = [
            'currentPage' => 1,
            'lastPage' => 1,
            'size' => 100,
            'total' => 2,
        ];
        $this->assertEquals(json_encode($expectedSerialize), json_encode($paginator->jsonSerialize()['meta']));

        $this->assertEquals(1, $paginator->page());
        $this->assertEquals(1, $paginator->numPages());
        $this->assertEquals(100, $paginator->limit());
        $this->assertEquals(2, $paginator->numResults());

        $expectedSerialize = [
            [
                'id' => 'ab4b18e9-0596-4e31-8747-253db434029e',
                'name' => 'Name2',
                'categoryId' => null,
                'price' => 1,
                'currency' => 'USD',
                'featured' => true,
            ],
            [
                'id' => '34104ca7-e89d-42fc-a4dc-94aa44253feb',
                'name' => 'Name3',
                'categoryId' => null,
                'price' => 1,
                'currency' => 'USD',
                'featured' => true,
            ],
        ];
        $this->assertEquals(json_encode($expectedSerialize), json_encode($paginator->jsonSerialize()['products']));

        $query = new RetrieveFeaturedProductsQuery(2, 2);
        $handler = new RetrieveFeaturedProductsQueryHandler($this->productsRepository);
        $paginator = $handler->__invoke($query);

        $expectedSerialize = [
            'currentPage' => 2,
            'lastPage' => 1,
            'size' => 2,
            'total' => 2,
        ];
        $this->assertEquals(json_encode($expectedSerialize), json_encode($paginator->jsonSerialize()['meta']));
    }

    protected function setUp(): void
    {
        $this->productsRepository = new InMemoryProductRepository();
    }
}
