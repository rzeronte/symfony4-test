<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Query\RetrieveFeaturedProducts;

use App\Application\Query\RetrieveFeaturedProducts\RetrieveFeaturedProductsQuery;
use App\Application\Query\RetrieveFeaturedProducts\RetrieveFeaturedProductsQueryHandler;
use App\Domain\Product\Product;
use App\Domain\Product\Service\CurrencyConversor;
use App\Domain\Product\ValueObject\ProductId;
use App\Domain\Product\ValueObject\ProductName;
use App\Domain\Product\ValueObject\ProductPrice;
use App\Infrastructure\Persistence\InMemory\InMemoryProductRepository;
use App\Shared\Application\ValueObject\Currency;
use App\Tests\Unit\Infrastructure\CurrencyConversor\FakeCurrencyConversor;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class RetrieveFeaturedProductsQueryHandlerTest extends TestCase
{
    private InMemoryProductRepository $productsRepository;
    private CurrencyConversor $currencyConversor;

    /**
     * @throws AssertionFailedException
     */
    public function testRetrieveFeaturedProductsWithEmptyRepository()
    {
        $query = new RetrieveFeaturedProductsQuery(null, 1, 50);
        $handler = new RetrieveFeaturedProductsQueryHandler($this->productsRepository, $this->currencyConversor);
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
                Currency::from('EUR'),
                true
            )
        );

        $query = new RetrieveFeaturedProductsQuery(null);
        $handler = new RetrieveFeaturedProductsQueryHandler($this->productsRepository, $this->currencyConversor);
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
                'currency' => 'EUR',
                'featured' => true,
            ],
        ];
        $this->assertEquals(json_encode($expectedSerialize), json_encode($paginator->jsonSerialize()['products']));

        $query = new RetrieveFeaturedProductsQuery(null, 2, 2);
        $handler = new RetrieveFeaturedProductsQueryHandler($this->productsRepository, $this->currencyConversor);
        $paginator = $handler->__invoke($query);

        $expectedSerialize = [
            'currentPage' => 2,
            'lastPage' => 1,
            'size' => 2,
            'total' => 2,
        ];
        $this->assertEquals(json_encode($expectedSerialize), json_encode($paginator->jsonSerialize()['meta']));
    }

    /**
     * @throws AssertionFailedException
     */
    public function testRetrieveFeaturedProductsWithCurrencyConversions()
    {
        $this->productsRepository->save(
            Product::create(
                ProductId::from('d5c509a1-3e74-4daf-9626-55e0c2665958'),
                ProductName::from('Name1'),
                null,
                ProductPrice::from(1),
                Currency::from('USD'),
                true
            )
        );

        $query = new RetrieveFeaturedProductsQuery('EUR');
        $handler = new RetrieveFeaturedProductsQueryHandler($this->productsRepository, $this->currencyConversor);
        $paginator = $handler->__invoke($query);

        $expectedSerialize = [
            [
                'id' => 'd5c509a1-3e74-4daf-9626-55e0c2665958',
                'name' => 'Name1',
                'categoryId' => null,
                'price' => 0.9238703144762164,
                'currency' => 'EUR',
                'featured' => true,
            ],
        ];
        $this->assertEquals(json_encode($expectedSerialize), json_encode($paginator->jsonSerialize()['products']));
    }

    protected function setUp(): void
    {
        $this->productsRepository = new InMemoryProductRepository();
        $this->currencyConversor = new FakeCurrencyConversor();
    }
}
