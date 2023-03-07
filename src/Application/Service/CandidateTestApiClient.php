<?php

namespace App\Application\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class CandidateTestApiClient
{
    private Client $client;

    /**
     * @param string $baseUri
     * @param float $timeout
     */
    public function __construct(string $baseUri, float $timeout = 2.0)
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'timeout' => $timeout,
        ]);
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @return array|null
     * @throws JsonException
     */
    public function getByParameters(string $endpoint, array $params): ?array
    {
        try {
            $response = $this->client->get($endpoint, $params);

            return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException) {
            return null;
        }
    }
}
