<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\CurrencyConversor;

use App\Domain\Product\Service\CurrencyConversor;
use App\Domain\Product\ValueObject\ProductPrice;
use App\Shared\Application\ValueObject\Currency;

class FakeCurrencyConversor implements CurrencyConversor
{
    private float $oneEurInUSD = 1.082403;

    public function convertPrice(ProductPrice $priceFrom, Currency $from, Currency $to): ProductPrice
    {
        if ('EUR' === $from->value() && 'USD' == $to->value()) {
            return ProductPrice::from($priceFrom->value() * $this->oneEurInUSD);
        }

        if ('USD' === $from->value() && 'EUR' == $to->value()) {
            return ProductPrice::from($priceFrom->value() / $this->oneEurInUSD);
        }
    }
}
