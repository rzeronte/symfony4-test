<?php

declare(strict_types=1);

namespace App\Application\Query\RetrieveProducts;

final class RetrieveProductsQuery
{
    private const DEFAULT_PAGE = 1;
    private const DEFAULT_LIMIT = 100;

    private ?bool $featured;
    private int $page;
    private int $limit;

    public function __construct(?bool $featured, ?int $page = null, ?int $limit = null)
    {
        $this->featured = $featured;
        $this->page = $page ?? self::DEFAULT_PAGE;
        $this->limit = $limit ?? self::DEFAULT_LIMIT;
    }

    public function isFeatured(): ?bool
    {
        return $this->featured;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
