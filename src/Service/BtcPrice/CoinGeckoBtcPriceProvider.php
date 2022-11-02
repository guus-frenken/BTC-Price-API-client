<?php

namespace App\Service\BtcPrice;

use DateTime;
use App\DTO\BtcPrice;
use App\Config\Currency;
use App\Service\HttpClient;
use App\DTO\BtcPriceCollection;
use App\Service\NumberFormatter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;

class CoinGeckoBtcPriceProvider implements BtcPriceProviderInterface
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly HttpClient $httpClient,
        private readonly NumberFormatter $numberFormatter,
        private readonly string $baseUri,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getPrice(Currency $currency): ?BtcPrice
    {
        return $this->cache->get("btc_price_{$currency->value}", function (ItemInterface $item) use ($currency) {
            $item->expiresAfter(3600);

            $response = $this->makeGetRequest(
                uri: 'simple/price',
                params: [
                    'ids' => 'bitcoin',
                    'vs_currencies' => strtolower($currency->value),
                ],
            );

            if (!isset($response->body->bitcoin->{strtolower($currency->value)})) {
                return null;
            }

            $price = $response->body->bitcoin->{strtolower($currency->value)};

            return new BtcPrice(
                price: $price,
                priceFormatted: $this->numberFormatter->formatCurrency(
                    number: $price,
                    currency: $currency
                ),
                timestamp: (new DateTime())->getTimestamp(),
            );
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
                    uri: 'coins/bitcoin/market_chart',
                    params: [
                        'id' => 'bitcoin',
                        'vs_currency' => strtolower($currency->value),
                        'days' => 30,
                        'interval' => 'daily',
                    ],
                );

                if (!isset($response->body->prices)) {
                    return new BtcPriceCollection();
                }

                $prices = $response->body->prices;

                return new BtcPriceCollection(
                    ...array_map(function ($price) use ($currency) {
                        return new BtcPrice(
                            price: $price[1],
                            priceFormatted: $this->numberFormatter->formatCurrency(
                                number: $price[1],
                                currency: $currency
                            ),
                            timestamp: $price[0],
                        );
                    }, $prices)
                );
            }
        );
    }

    /**
     * Send a request to the CoinGecko API
     */
    private function makeGetRequest(string $uri, array $params = [], array $headers = []): object
    {
        return $this->httpClient->makeGetRequest(
            baseUri: $this->baseUri,
            uri: $uri,
            urlParams: $params,
            headers: $headers,
        );
    }
}
