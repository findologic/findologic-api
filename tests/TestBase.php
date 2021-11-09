<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests;

use FINDOLOGIC\GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TestBase extends TestCase
{
    /** @var Client|MockObject */
    protected $httpClientMock;

    /** @var Response|MockObject */
    protected $responseMock;

    /** @var Stream|MockObject */
    protected $streamMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->httpClientMock = $this->getMockBuilder(Client::class)
            ->onlyMethods(['request'])
            ->getMock();
        $this->responseMock = $this->getMockBuilder(Response::class)
            ->onlyMethods(['getBody', 'getStatusCode'])
            ->getMock();
        $this->streamMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getContents'])
            ->getMock();
    }

    /**
     * Will set the default expectations for doing a request, which is required by all tests that are dealing with
     * sending requests.
     * @param array<string, mixed> $guzzleOptionsOverride
     */
    protected function setExpectationsForRequests(
        string $expectedRequestUrl,
        string $expectedBody,
        int $statusCode = 200,
        string $requestMethod = 'GET',
        array $guzzleOptionsOverride = []
    ): void {
        $this->httpClientMock->method('request')
            ->with(
                $this->equalTo($requestMethod),
                $expectedRequestUrl,
                array_merge(['connect_timeout' => 3.0], $guzzleOptionsOverride)
            )
            ->willReturnOnConsecutiveCalls($this->responseMock);
        $this->responseMock->method('getBody')
            ->with()
            ->willReturnOnConsecutiveCalls($this->streamMock, $this->streamMock);
        $this->responseMock->method('getStatusCode')
            ->with()
            ->willReturnOnConsecutiveCalls($statusCode);
        $this->streamMock->method('getContents')
            ->with()
            ->willReturnOnConsecutiveCalls($expectedBody, $expectedBody);
    }

    /**
     * Will set the default expectations for doing an alivetest request, which is required by all tests that are dealing
     * with sending alivetest requests.
     */
    protected function setExpectationsForAliveTestRequests(
        string $expectedSearchRequestUrl,
        string $expectedRequestUrl,
        string $expectedBody,
        string $expectedAlivetestBody = 'alive'
    ): void {
        $this->httpClientMock->method('request')
            ->withConsecutive(
                ['GET', $expectedRequestUrl, ['connect_timeout' => 1.0]],
                ['GET', $expectedSearchRequestUrl, ['connect_timeout' => 3.0]]
            )
            ->willReturnOnConsecutiveCalls($this->responseMock, $this->responseMock);
        $this->responseMock->method('getBody')
            ->with()
            ->willReturnOnConsecutiveCalls($this->streamMock, $this->streamMock, $this->streamMock);
        $this->responseMock->method('getStatusCode')
            ->with()
            ->willReturnOnConsecutiveCalls(200);
        $this->streamMock->method('getContents')
            ->with()
            ->willReturnOnConsecutiveCalls($expectedAlivetestBody, $expectedBody, $expectedBody);
    }

    protected function getMockResponse(string $file): string
    {
        return file_get_contents(__DIR__ . '/Mockdata/' . $file);
    }
}
