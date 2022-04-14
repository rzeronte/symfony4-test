<?php

declare(strict_types=1);

namespace App\Application\Query\RetrieveFeaturedProducts;

use App\Domain\Product\Product;
use App\Domain\Product\Service\CurrencyConversor;
use App\Shared\Application\Query\PaginatorResponse;
use App\Shared\Application\ValueObject\Currency;
use JsonSerializable;

final class RetrieveFeaturedProductsResponse extends PaginatorResponse implements JsonSerializable
{
    private CurrencyConversor $currencyConversor;

    private function __construct(
        array $results,
        int $page,
        int $limit,
        int $numResults,
        ?Currency $currency,
        CurrencyConversor $currencyConversor
    ) {
        parent::__construct($results, $page, $limit, $numResults);

        $this->currencyConversor = $currencyConversor;

        if (null !== $currency) {
            $this->transformProductsToCurrency($currency);
        }
    }

    public static function write(
        array $results,
        int $page,
        int $limit,
        int $numResults,
        ?Currency $currency,
        CurrencyConversor $currencyConversor
    ): self {
        return new self($results, $page, $limit, $numResults, $currency, $currencyConversor);
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

    private function transformProductsToCurrency(Currency $currency)
    {
        foreach ($this->results() as $product) {
            /** @var $product Product */
            if ($product->getCurrency()->value() !== $currency->value()) {
                $product->convertTo(
                    $currency,
                    $this->currencyConversor->convertPrice($product, $currency)
                );
            }
        }
    }
}
