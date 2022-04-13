<?php

declare(strict_types=1);

namespace App\Application\Query\RetrieveProducts;

use App\Domain\Product\Repository\ProductRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RetrieveProductsQueryHandler implements MessageHandlerInterface
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(RetrieveProductsQuery $query): ListProductsResponse
    {
        return ListProductsResponse::write(
            $this->repository->search($query->isFeatured(), $query->getPage(), $query->getLimit()),
            $query->getPage(),
            $query->getLimit(),
            $this->repository->searchCount($query->isFeatured())
        );
    }
}
