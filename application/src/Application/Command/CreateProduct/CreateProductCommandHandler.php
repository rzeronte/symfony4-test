<?php

declare(strict_types=1);

namespace App\Application\Command\CreateProduct;

use App\Domain\Category\Repository\CategoryRepository;
use App\Domain\Category\ValueObject\CategoryId;
use App\Domain\Product\Product;
use App\Domain\Product\Repository\ProductRepository;
use App\Domain\Product\ValueObject\ProductId;
use App\Domain\Product\ValueObject\ProductName;
use App\Domain\Product\ValueObject\ProductPrice;
use App\Shared\Application\ValueObject\Currency;
use Assert\AssertionFailedException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateProductCommandHandler implements MessageHandlerInterface
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @throws AssertionFailedException
     */
    public function __invoke(CreateProductCommand $command)
    {
        $product = Product::create(
            ProductId::from($command->getId()),
            ProductName::from($command->getName()),
            (null !== $command->getCategoryId()) ?
                $this->categoryRepository->ofIdOrFail(CategoryId::from($command->getCategoryId())) : null,
            ProductPrice::from($command->getPrice()),
            Currency::from($command->getCurrency()),
            $command->isFeatured()
        );

        $this->productRepository->save($product);
    }
}
