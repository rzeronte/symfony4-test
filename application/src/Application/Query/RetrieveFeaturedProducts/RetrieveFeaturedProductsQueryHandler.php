<?php

declare(strict_types=1);

namespace App\Application\Query\RetrieveFeaturedProducts;

use App\Domain\Product\Repository\ProductRepository;
use App\Domain\Product\Service\CurrencyConversor;
use App\Shared\Application\ValueObject\Currency;
use Assert\AssertionFailedException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RetrieveFeaturedProductsQueryHandler implements MessageHandlerInterface
{
    private ProductRepository $repository;

    private CurrencyConversor $currencyConversor;

    public function __construct(ProductRepository $repository, CurrencyConversor $currencyConversor)
    {
        $this->repository = $repository;
        $this->currencyConversor = $currencyConversor;
    }

    /**
     * @throws AssertionFailedException
     */
    public function __invoke(RetrieveFeaturedProductsQuery $query): RetrieveFeaturedProductsResponse
    {
        return RetrieveFeaturedProductsResponse::write(
            $this->repository->search(true, $query->getPage(), $query->getLimit()),
            $query->getPage(),
            $query->getLimit(),
            $this->repository->searchCount(true),
            (null !== $query->getCurrency()) ? Currency::from($query->getCurrency()) : null,
            $this->currencyConversor
        );
    }
}
