<?php

declare(strict_types=1);

namespace App\Domain\Product\Service;

use App\Domain\Product\ValueObject\ProductPrice;
use App\Shared\Application\ValueObject\Currency;

interface CurrencyConversor
{
    public function convertPrice(ProductPrice $priceFrom, Currency $from, Currency $to): ProductPrice;
}
