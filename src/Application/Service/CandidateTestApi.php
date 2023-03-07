<?php

namespace App\Application\Service;

use Exception;
use JsonException;

class CandidateTestApi
{
    private string $baseUri = 'https://candidate-test.sq1.io';

    /**
     * @param array $params
     * @return array
     * @throws JsonException
     * @throws Exception
     */
    public function getByParameters(array $params): ?array
    {
        $client = new CandidateTestApiClient($this->baseUri);
        $response = $client->getByParameters('/api.php', $params);

        if (empty($response)) {
            throw new Exception('La API no devolvió datos');
        }

        return $response;
    }
}
