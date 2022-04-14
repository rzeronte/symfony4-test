<?php

declare(strict_types=1);

namespace App\Application\Query\RetrieveFeaturedProducts;

use App\Application\Query\RetrieveProducts\ListProductsResponse;
use App\Domain\Product\Repository\ProductRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RetrieveFeaturedProductsQueryHandler implements MessageHandlerInterface
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(RetrieveFeaturedProductsQuery $query): ListProductsResponse
    {
        return ListProductsResponse::write(
            $this->repository->search(true, $query->getPage(), $query->getLimit()),
            $query->getPage(),
            $query->getLimit(),
            $this->repository->searchCount(true)
        );
    }
}
