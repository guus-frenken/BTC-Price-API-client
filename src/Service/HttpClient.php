<?php

namespace App\Service;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use App\Config\RequestMethod;
use GuzzleHttp\Exception\GuzzleException;

class HttpClient
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * Perform a GET request
     */
    public function makeGetRequest(
        string $baseUri,
        string $uri,
        array $urlParams = [],
        array $headers = [],
    ): object {
        return $this->makeRequest(
            baseUri: $baseUri,
            method: RequestMethod::GET,
            uri: $uri,
            params: [
                'query' => $urlParams,
                'headers' => $headers,
            ],
        );
    }

    /**
     * Perform a POST request
     */
    public function makePostRequest(
        string $baseUri,
        string $uri,
        array $body = [],
        array $headers = [],
    ): object {
        return $this->makeRequest(
            baseUri: $baseUri,
            method: RequestMethod::POST,
            uri: $uri,
            params: [
                'form_params' => $body,
                'headers' => $headers,
            ],
        );
    }

    /**
     * Send an HTTP request
     */
    private function makeRequest(
        string $baseUri,
        RequestMethod $method,
        string $uri,
        array $params = [],
    ): object {
        $output = (object)[];

        $output->body = [];

        try {
            $response = (new Client(['base_uri' => $baseUri]))->request($method->value, $uri, $params);

            $output->body = json_decode($response->getBody(), false);
            $output->statusCode = $response->getStatusCode();
        } catch (GuzzleException $e) {
            $output->statusCode = $e->getCode();

            $this->logger->error($e->getMessage());
        }

        return $output;
    }
}
