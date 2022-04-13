<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Product\Product;
use App\Domain\Product\Repository\ProductRepository;
use App\Domain\Product\ValueObject\ProductId;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ObjectRepository;

final class DoctrineProductRepository implements ProductRepository
{
    private ObjectRepository $repository;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Product::class);
    }

    public function save(Product $product): void
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    /** @throws QueryException
     * @throws AssertionFailedException
     */
    public function search(?bool $isFeatured, int $numPage = 0, int $limit = 0): array
    {
        Assertion::greaterOrEqualThan($numPage, 1, 'Page must be greater or equal than 1');
        Assertion::greaterOrEqualThan($limit, 1, 'Limit must be greater or equal than 1');

        $criteria = Criteria::create()->setFirstResult(($numPage - 1) * $limit)->setMaxResults($limit);

        if ($isFeatured) {
            $criteria->where(Criteria::expr()->eq('featured', $isFeatured));
        }

        return $this->entityManager->createQueryBuilder()
            ->select('c')
            ->from(Product::class, 'c')
            ->addCriteria($criteria)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws QueryException
     * @throws NoResultException
     */
    public function searchCount(?bool $isFeatured): int
    {
        $criteria = Criteria::create();

        if ($isFeatured) {
            $criteria->where(Criteria::expr()->eq('featured', $isFeatured));
        }

        return (int) $this->entityManager->createQueryBuilder()
            ->select('count(c.id)')->from(Product::class, 'c')
            ->addCriteria($criteria)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function ofId(ProductId $productId): ?Product
    {
        return $this->repository->find($productId->value());
    }
}
