<?php

namespace App\Application\Service;

use JsonException;

class CandidateTestApi
{
    private string $baseUri = 'https://candidate-test.sq1.io';

    /**
     * @param array $params
     * @return array
     * @throws JsonException
     */
    public function getByParameters(array $params): array
    {
        return (new CandidateTestApiClient($this->baseUri))->getByParameters('/api.php', $params);
    }
}
