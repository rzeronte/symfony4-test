<?php

declare(strict_types=1);

namespace App\Infrastructure\CurrencyConversor;

use App\Domain\Product\Service\CurrencyConversor;
use App\Domain\Product\ValueObject\ProductPrice;
use App\Shared\Application\ValueObject\Currency;
use Assert\AssertionFailedException;
use Goutte\Client;

class CurrencyConversorExchangeRatesApi implements CurrencyConversor
{
    private const URL = 'http://api.exchangeratesapi.io/v1/latest?access_key=%s&base=EUR&symbols=USD';

    private Client $client;

    private float $oneEurInUSD = 1.082403;
    private string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
        $this->client = new Client();
    }

    private function getLatestValues()
    {
        $response = $this->client->request('GET', sprintf(self::URL, $this->key));

        print_r($response);
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
