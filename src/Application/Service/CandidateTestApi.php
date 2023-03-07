<?php

namespace App\Application\Service;

use JsonException;

class CandidateTestApi
{
    private string $baseUri = 'https://candidate-test.sq1.io';

    /**
     * @param array $params
     * @return array|null
     * @throws JsonException
     */
    public function getByParameters(array $params): ?array
    {
        $client = new CandidateTestApiClient($this->baseUri);
        $response = $client->getByParameters('/api.php', $params);

        if (empty($response)) {
            throw new \RuntimeException('La API no devolvió datos');
        }

        return $response;
    }
}
