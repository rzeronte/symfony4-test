<?php

declare(strict_types=1);

namespace App\Application\Query\RetrieveFeaturedProducts;

final class RetrieveFeaturedProductsQuery
{
    private const DEFAULT_PAGE = 1;
    private const DEFAULT_LIMIT = 100;

    private int $page;
    private int $limit;
    private ?string $currency;

    public function __construct(?string $currency, ?int $page = null, ?int $limit = null)
    {
        $this->currency = $currency;
        $this->page = $page ?? self::DEFAULT_PAGE;
        $this->limit = $limit ?? self::DEFAULT_LIMIT;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }
}
