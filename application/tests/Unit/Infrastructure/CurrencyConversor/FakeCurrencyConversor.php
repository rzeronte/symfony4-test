<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\CurrencyConversor;

use App\Domain\Product\Product;
use App\Domain\Product\Service\CurrencyConversor;
use App\Domain\Product\ValueObject\ProductPrice;
use App\Shared\Application\ValueObject\Currency;
use Assert\AssertionFailedException;

class FakeCurrencyConversor implements CurrencyConversor
{
    private float $oneEurInUSD = 1.082403;

    /**
     * @throws AssertionFailedException
     */
    public function convertPrice(Product $product, Currency $to): ProductPrice
    {
        if ('EUR' === $product->getCurrency()->value() && 'USD' == $to->value()) {
            return ProductPrice::from($product->getPrice()->value() * $this->oneEurInUSD);
        }

        if ('USD' === $product->getCurrency()->value() && 'EUR' == $to->value()) {
            return ProductPrice::from($product->getPrice()->value() / $this->oneEurInUSD);
        }

        return $product->getPrice();
    }
}
