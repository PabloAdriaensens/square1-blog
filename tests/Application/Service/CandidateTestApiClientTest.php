<?php

namespace Tests\App\Application\Service;

use App\Application\Service\CandidateTestApiClient;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class CandidateTestApiClientTest extends TestCase
{
    private CandidateTestApiClient $apiClient;
    private Client $clientMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientMock = $this->createMock(Client::class);
        $this->apiClient = new CandidateTestApiClient('https://candidate-test.sq1.io');
        $clientProperty = new \ReflectionProperty($this->apiClient, 'client');
        $clientProperty->setValue($this->apiClient, $this->clientMock);
    }

    /**
     * @throws \JsonException
     */
    public function testGetByParametersReturnsArray(): void
    {
        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/endpoint', ['param1' => 'value1', 'param2' => 'value2'])
            ->willReturn(new Response(200, [], json_encode(['data' => 'result'], JSON_THROW_ON_ERROR)));

        $result = $this->apiClient->getByParameters('/endpoint', ['param1' => 'value1', 'param2' => 'value2']);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('result', $result['data']);
    }

    public function testGetByParametersReturnsNullOnTimeout(): void
    {
        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/endpoint', ['param1' => 'value1', 'param2' => 'value2'])
            ->willThrowException(new \GuzzleHttp\Exception\ConnectException('Connection timed out', new \GuzzleHttp\Psr7\Request('GET', '/')));

        $result = $this->apiClient->getByParameters('/endpoint', ['param1' => 'value1', 'param2' => 'value2']);

        $this->assertNull($result);
    }

}
