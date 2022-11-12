<?php

namespace App\Service\BtcPrice;

use App\Service\HttpClient;

class CoinrankingApiClient
{
    public function __construct(
        private readonly HttpClient $httpClient,
        private readonly string $baseUri,
        private readonly string $apiKey,
    ) {
    }

    /**
     * Send a GET request to the Coinranking API
     */
    public function get(string $uri, array $params = [], array $headers = []): object
    {
        return $this->httpClient->get(
            baseUri: $this->baseUri,
            uri: $uri,
            urlParams: $params,
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
