<?php

declare(strict_types=1);

namespace App\Infrastructure\CurrencyConversor;

use App\Domain\Product\Service\CurrencyConversor;
use App\Domain\Product\ValueObject\ProductPrice;
use App\Shared\Application\ValueObject\Currency;
use Assert\AssertionFailedException;
use Redis;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

class CurrencyConversorExchangeRatesApi implements CurrencyConversor
{
    private const URL = 'http://api.exchangeratesapi.io/v1/latest?access_key=%s&base=EUR&symbols=USD';
    private const KEY = 'eur_in_usd';
    private float $oneEurInUSD;
    private string $key;
    private Redis $cache;

    /**
     * @throws InvalidArgumentException|\Psr\Cache\InvalidArgumentException
     */
    public function __construct(string $key, Redis $redis)
    {
        $this->key = $key;
        $this->cache = $redis;

        if ($value = $this->cache->get(self::KEY)) {
            $this->oneEurInUSD = (float) $value;

            return;
        }

        $this->oneEurInUSD = $this->getLatestValues();
        $this->cache->setex(self::KEY, 3600, $this->oneEurInUSD);
    }

    private function getLatestValues(): float
    {
        $data = json_decode(file_get_contents(sprintf(self::URL, $this->key)));

        return (float) $data->rates->USD;
    }

    /**
     * @throws AssertionFailedException
     */
    public function convertPrice(ProductPrice $priceFrom, Currency $from, Currency $to): ProductPrice
    {
        if ('EUR' === $from->value() && 'USD' == $to->value()) {
            return ProductPrice::from($priceFrom->value() * $this->oneEurInUSD);
        }

        if ('USD' === $from->value() && 'EUR' == $to->value()) {
            return ProductPrice::from($priceFrom->value() / $this->oneEurInUSD);
        }

        return $priceFrom;
    }
}
