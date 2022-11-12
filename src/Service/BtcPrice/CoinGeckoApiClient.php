<?php

namespace App\Service\BtcPrice;

use App\Service\HttpClient;

class CoinGeckoApiClient
{
    public function __construct(
        private readonly HttpClient $httpClient,
        private readonly string $baseUri,
    ) {
    }

    /**
     * Send a GET request to the CoinGecko API
     */
    public function get(string $uri, array $params = [], array $headers = []): object
    {
        return $this->httpClient->get(
            baseUri: $this->baseUri,
            uri: $uri,
            urlParams: $params,
            headers: $headers,
        );
    }
}
