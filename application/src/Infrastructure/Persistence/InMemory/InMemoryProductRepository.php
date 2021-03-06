<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\InMemory;

use App\Domain\Product\Product;
use App\Domain\Product\Repository\ProductRepository;
use App\Domain\Product\ValueObject\ProductId;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

final class InMemoryProductRepository implements ProductRepository
{
    /**
     * @var Collection<string, Product>
     */
    private Collection $products;

    public function __construct(array $products = [])
    {
        $this->products = new ArrayCollection([]);

        foreach ($products as $product) {
            $this->save($product);
        }
    }

    public function save(Product $product): void
    {
        $this->products->set($product->getId()->value(), $product);
    }

    /**
     * @throws AssertionFailedException
     */
    public function search(?bool $isFeatured, int $numPage, int $limit): array
    {
        Assertion::greaterOrEqualThan($numPage, 1, 'Page must be greater or equal than 1');
        Assertion::greaterOrEqualThan($limit, 1, 'Limit must be greater or equal than 1');

        $results = $this->products;

        $criteria = Criteria::create()
            ->setFirstResult(($numPage - 1) * $limit)
            ->setMaxResults($limit)
        ;

        if (null !== $isFeatured) {
            $results = $this->products->filter(
                static fn (Product $product) => $product->getFeatured() === $isFeatured,
            );
        }

        return $results->matching($criteria)->toArray();
    }

    public function searchCount(?bool $isFeatured): int
    {
        $results = $this->products;
        $criteria = Criteria::create();

        if (null !== $isFeatured) {
            $results = $this->products->filter(
                static fn (Product $product) => $product->getFeatured() === $isFeatured,
            );
        }

        return $results->matching($criteria)->count();
    }

    public function ofId(ProductId $productId): ?Product
    {
        return $this->products->get($productId->value());
    }
}
