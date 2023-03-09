<?php

namespace Tests\App\Application\Service;

use App\Application\Service\CandidateTestApi;
use App\Application\Service\CandidateTestApiClient;
use PHPUnit\Framework\TestCase;

class CandidateTestApiTest extends TestCase
{
    private CandidateTestApi $candidateTestApi;

    protected function setUp(): void
    {
        $client = new CandidateTestApiClient('https://candidate-test.sq1.io');
        $this->candidateTestApi = new CandidateTestApi($client);
    }

    public function testGetByParametersReturnsArray(): void
    {
        $params = [
            'param1' => 'value1',
            'param2' => 'value2',
        ];

        $result = $this->candidateTestApi->getByParameters($params);

        $this->assertIsArray($result);
    }
}
