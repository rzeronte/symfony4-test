<?php

declare(strict_types=1);

namespace App\Shared\Application\Query;

abstract class PaginatorResponse
{
    /**
     * @phpstan-ignore-next-line
     */
    private array $results;
    private int $page;
    private int $limit;
    private int $numResults;

    /**
     * @phpstan-ignore-next-line
     */
    protected function __construct(array $results, int $page, int $limit, int $numResults)
    {
        $this->results = array_values($results);
        $this->page = $page;
        $this->limit = $limit;
        $this->numResults = $numResults;
    }

    /**
     * @phpstan-ignore-next-line
     */
    protected function results(): array
    {
        return $this->results;
    }

    protected function page(): int
    {
        return $this->page;
    }

    protected function limit(): int
    {
        return $this->limit;
    }

    protected function numResults(): int
    {
        return $this->numResults;
    }

    /**
     * @psalm-suppress DivisionByZeroError
     */
    protected function numPages(): int
    {
        return (int) ceil($this->numResults / $this->limit);
    }
}
