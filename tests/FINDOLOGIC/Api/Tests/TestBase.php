<?php

namespace FINDOLOGIC\Api\Tests;

use FINDOLOGIC\Api\RequestBuilders\Xml\XmlRequestBuilder;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class TestBase extends TestCase
{
    /** @var Client|PHPUnit_Framework_MockObject_MockObject */
    protected $httpClientMock;

    /** @var Response|PHPUnit_Framework_MockObject_MockObject */
    protected $responseMock;

    /** @var Stream|PHPUnit_Framework_MockObject_MockObject */
    protected $streamMock;

    protected function setUp()
    {
        parent::setUp();
        $this->httpClientMock = $this->getMockBuilder(Client::class)
            ->setMethods(['request'])
            ->getMock();
        $this->responseMock = $this->getMockBuilder(Response::class)
            ->setMethods(['getBody', 'getStatusCode'])
            ->getMock();
        $this->streamMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getContents'])
            ->getMock();
    }

    /**
     * Will set the default expectations for doing a request, which is required by all tests that are dealing with
     * sending requests.
     *
     * @param string $expectedRequestUrl
     * @param string $expectedBody
     * @param int $statusCode
     */
    protected function setExpectationsForRequests($expectedRequestUrl, $expectedBody, $statusCode = 200)
    {
        $this->httpClientMock->method('request')
            ->with('GET', $expectedRequestUrl, ['connect_timeout' => 3.0])
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
     *
     * @param string $expectedRequestUrl
     * @param string $expectedBody
     */
    protected function setExpectationsForAliveTestRequests($expectedRequestUrl, $expectedBody)
    {
        $this->httpClientMock->method('request')
            ->with('GET', $expectedRequestUrl, ['connect_timeout' => 1.0])
            ->willReturnOnConsecutiveCalls($this->responseMock);
        $this->responseMock->method('getBody')
            ->with()
            ->willReturnOnConsecutiveCalls($this->streamMock, $this->streamMock);
        $this->responseMock->method('getStatusCode')
            ->with()
            ->willReturnOnConsecutiveCalls(200);
        $this->streamMock->method('getContents')
            ->with()
            ->willReturnOnConsecutiveCalls($expectedBody, $expectedBody);
    }

    protected function getMockResponse($file)
    {
        return file_get_contents(__DIR__ . '/../Mockdata/' . $file);
    }

    protected function setRequiredParamsForXmlRequestBuilder(XmlRequestBuilder &$xmlRequestBuilder)
    {
        $xmlRequestBuilder
            ->setShopurl('blubbergurken.io')
            ->setUserip('127.0.0.1')
            ->setReferer('https://blubbergurken.io/blubbergurken-sale/')
            ->setRevision('2.5.10');
    }
}
