<?php

declare(strict_types=1);

namespace App\Infrastructure\Behat\Context;

use App\Domain\Category\Category;
use App\Domain\Category\Repository\CategoryRepository;
use App\Domain\Category\ValueObject\CategoryDescription;
use App\Domain\Category\ValueObject\CategoryId;
use App\Domain\Category\ValueObject\CategoryName;
use App\Domain\Product\Product;
use App\Domain\Product\Repository\ProductRepository;
use App\Domain\Product\ValueObject\ProductId;
use App\Domain\Product\ValueObject\ProductName;
use App\Domain\Product\ValueObject\ProductPrice;
use App\Shared\Application\ValueObject\Currency;
use Assert\AssertionFailedException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;

final class ApplicationContext implements Context
{
    private const EXCLUDED_TABLES = [];

    private static EntityManagerInterface $entityManager;
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository
    ) {
        self::$entityManager = $entityManager;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @BeforeFeature
     * @AfterScenario @PurgeDatabase
     */
    public static function purgeDatabase()
    {
        $purger = new ORMPurger(self::$entityManager, self::EXCLUDED_TABLES);
        $purger->purge();
    }

    /**
     * @Given  /^A category that exists:$/
     * @throws AssertionFailedException
     */
    public function aCategoryThatExists(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $category = Category::create(
                CategoryId::from($row['id']),
                CategoryName::from($row['name']),
                CategoryDescription::from($row['description']),
            );

            $this->categoryRepository->save($category);
        }
    }

    /**
     * @Given /^A product that exists:$/
     * @throws AssertionFailedException
     */
    public function aProductThatExists(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $category = Product::create(
                ProductId::from($row['id']),
                ProductName::from($row['name']),
                null,
                ProductPrice::from((float) $row['price']),
                Currency::from($row['currency']),
                (bool) $row['featured']
            );

            $this->productRepository->save($category);
        }
    }
}
