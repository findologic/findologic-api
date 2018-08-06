<?php

namespace FINDOLOGIC_DEV\Tests;

use FINDOLOGIC_DEV\FindologicApi;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FindologicApiTest extends TestCase
{
    /** @var $httpClientMock MockObject */
    public $httpClientMock;

    /** @var $httpClientMock MockObject */
    public $responseMock;

    public function setUp()
    {
        $this->httpClientMock = $this->getMockBuilder(Client::class)
            ->setMethods(['request'])
            ->getMock();

        $this->responseMock = $this->getMockBuilder(Response::class)
            ->setMethods(['getBody', 'getStatusCode'])
            ->getMock();
    }

    /**
     * Sets default expectations that are required in basically every test that should work when requesting.
     */
    public function setDefaultExpectations()
    {
        // Alivetest.
        $this->responseMock->expects($this->at(0))->method('getBody')->willReturn('alive');
        $this->responseMock->expects($this->at(1))->method('getStatusCode')->willReturn(200);

        // Search, Navigation or Suggest request.
        $this->responseMock->expects($this->at(2))->method('getBody')->willReturn('alive');
        $this->responseMock->expects($this->at(3))->method('getStatusCode')->willReturn(200);

        // Both requests should respond with the responseMock.
        $this->httpClientMock->expects($this->at(0))->method('request')->willReturn($this->responseMock);
        $this->httpClientMock->expects($this->at(1))->method('request')->willReturn($this->responseMock);
    }

    public function getDefaultFindologicApi()
    {
        return new FindologicApi([
            FindologicApi::SHOPKEY => '80AB18D4BE2654A78244106AD315DC2C',
            FindologicApi::HTTP_CLIENT => $this->httpClientMock
        ]);
    }

    public function requestProvider()
    {
        return [
            ['sendSearchRequest'],
            ['sendNavigationRequest'],
            ['sendSuggestionRequest'],
        ];
    }

    /**
     * @dataProvider requestProvider
     * @param $requestType
     */
    public function testAlivetestWorks($requestType)
    {
        $this->setDefaultExpectations();
        $findologicApi = $this->getDefaultFindologicApi();

        $findologicApi
            ->setShopurl('www.blubbergurken.io')
            ->setUserip('127.0.0.1')
            ->setReferer('www.blubbergurken.io/blubbergurken-sale')
            ->setRevision('1.0.0');

        $findologicApi->{$requestType}();
    }

    public function testRequiredParamShopurlMissingWillThrowAnException()
    {
        //$this->setDefaultExpectations();
        //TODO: Implement tests for all params missing.
    }
}
