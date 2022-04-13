<?php

declare(strict_types=1);

namespace App\Application\Query\RetrieveProducts;

use App\Shared\Application\Query\PaginatorResponse;
use JsonSerializable;

final class ListProductsResponse extends PaginatorResponse implements JsonSerializable
{
    private function __construct(array $results, int $page, int $limit, int $numResults)
    {
        parent::__construct($results, $page, $limit, $numResults);
    }

    public static function write(array $results, int $page, int $limit, int $numResults): self
    {
        return new self($results, $page, $limit, $numResults);
    }

    public function jsonSerialize(): array
    {
        return [
            'products' => $this->results(),
            'meta' => [
                'currentPage' => $this->page(),
                'lastPage' => $this->numPages(),
                'size' => $this->limit(),
                'total' => $this->numResults(),
            ],
        ];
    }
}
