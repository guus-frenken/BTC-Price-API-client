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
     * Send an HTTP request
     *
     * @param string $baseUri
     * @param RequestMethod $method
     * @param string $uri
     * @param array $params
     * @param array $headers
     *
     * @return object
     */
    public function makeRequest(
        string $baseUri,
        RequestMethod $method,
        string $uri,
        array $params = [],
        array $headers = []
    ): object {
        $output = (object)[];

        $output->body = [];

        try {
            $response = (new Client(['base_uri' => $baseUri]))->request(
                $method->value,
                $uri,
                [
                    'headers' => $headers,
                    ...$params,
                ],
            );

            $output->body = json_decode($response->getBody(), false);
            $output->statusCode = $response->getStatusCode();
        } catch (GuzzleException $e) {
            $output->statusCode = $e->getCode();

            $this->logger->error($e->getMessage());
        }

        return $output;
    }
}
