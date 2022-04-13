<?php

declare(strict_types=1);

namespace App\Domain\Product\Repository;

use App\Domain\Product\Product;
use App\Domain\Product\ValueObject\ProductId;

interface ProductRepository
{
    public function save(Product $product): void;

    public function search(?bool $isFeatured, int $numPage, int $limit): array;

    public function searchCount(?bool $isFeatured): int;

    public function ofId(ProductId $productId): ?Product;
}
