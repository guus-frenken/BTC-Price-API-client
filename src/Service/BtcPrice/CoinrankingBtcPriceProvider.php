<?php

namespace App\Service\BtcPrice;

use App\DTO\BtcPrice;
use App\Config\Currency;
use App\Service\HttpClient;
use App\Config\RequestMethod;
use App\DTO\BtcPriceCollection;
use App\Service\NumberFormatter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;

class CoinrankingBtcPriceProvider implements BtcPriceProviderInterface
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly HttpClient $httpClient,
        private readonly NumberFormatter $numberFormatter,
        private readonly string $baseUri,
        private readonly string $apiKey,
        private readonly string $btcUuid = 'Qwsogvtv82FCd',
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getPrice(Currency $currency): BtcPrice
    {
        return $this->cache->get("btc_price_{$currency->value}", function (ItemInterface $item) use ($currency) {
            $item->expiresAfter(3600);

            $response = $this->makeGetRequest(
                uri: "coin/{$this->btcUuid}/price",
                params: [
                    'referenceCurrencyUuid' => $this->getCurrencyUuid($currency),
                ],
            );

            return BtcPrice::create([
                'price' => $response->body->data->price,
                'priceFormatted' => $this->numberFormatter->formatCurrency(
                    $response->body->data->price,
                    currency: $currency
                ),
                'timestamp' => $response->body->data->timestamp,
            ]);
        });
    }

    /**
     * @inheritDoc
     */
    public function get30DayPriceHistory(Currency $currency): BtcPriceCollection
    {
        return $this->cache->get(
            "btc_price_history_{$currency->value}",
            function (ItemInterface $item) use ($currency) {
                $item->expiresAfter(3600);

                $response = $this->makeGetRequest(
                    uri: "coin/{$this->btcUuid}/history",
                    params: [
                        'timePeriod' => "30d",
                        'interval' => 'day',
                        'referenceCurrencyUuid' => $this->getCurrencyUuid($currency),
                    ],
                );

                $prices = $response->body->data->history;

                // Coinranking API returns prices at a 1hr interval, so only add every 24th item to the BtcPriceCollection
                $prices = array_filter($prices, function ($index) {
                    return $index % 24 == 0;
                }, ARRAY_FILTER_USE_KEY);

                return new BtcPriceCollection(
                    ...array_map(function ($price) use ($currency) {
                        return BtcPrice::create([
                            'price' => $price->price,
                            'priceFormatted' => $this->numberFormatter->formatCurrency(
                                $price->price,
                                currency: $currency
                            ),
                            'timestamp' => ($price->timestamp * 1000),
                        ]);
                    }, $prices)
                );
            }
        );
    }

    /**
     * Get the appropriate currency UUID for the Coinranking API
     */
    private function getCurrencyUuid(Currency $currency): string
    {
        return match ($currency) {
            Currency::EUR => '5k-_VTxqtCEI',
            Currency::USD => 'yhjMzLPhuIDl',
        };
    }

    /**
     * Send a GET request to the Coinranking API
     */
    private function makeGetRequest(string $uri, array $params = [], array $headers = []): object
    {
        return $this->makeRequest(RequestMethod::GET, $uri, $params, $headers);
    }

    /**
     * Send a request to the Coinranking API
     */
    private function makeRequest(RequestMethod $method, string $uri, array $params = [], array $headers = []): object
    {
        return $this->httpClient->makeRequest(
            baseUri: $this->baseUri,
            method: $method,
            uri: $uri,
            params: ['query' => $params],
            headers: [...$this->authParams(), ...$headers],
        );
    }

    /**
     * Get an array of the auth params required to make an API call
     */
    private function authParams(): array
    {
        return ['x-access-token' => $this->apiKey];
    }
}
