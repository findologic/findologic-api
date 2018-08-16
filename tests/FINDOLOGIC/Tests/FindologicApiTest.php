<?php

namespace FINDOLOGIC\Tests;

use FINDOLOGIC\Exceptions\ConfigException;
use FINDOLOGIC\Exceptions\ParamNotSetException;
use FINDOLOGIC\FindologicApi;
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
        // Get contents from a real response locally.
        $realResponseData = file_get_contents(__DIR__ . '/../Mockdata/demoResponse.xml');

        // Alivetest.
        $this->responseMock->expects($this->at(0))->method('getBody')->willReturn('alive');
        $this->responseMock->expects($this->at(1))->method('getStatusCode')->willReturn(200);

        // Search, Navigation or Suggest request.
        $this->responseMock->expects($this->at(2))->method('getBody')->willReturn($realResponseData);
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
     * @param $requestType string
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

    /**
     * @dataProvider requestProvider
     * @param $requestType string
     */
    public function testRequiredParamShopurlMissingWillThrowAnException($requestType)
    {
        $findologicApi = $this->getDefaultFindologicApi();

        $findologicApi
            ->setUserip('127.0.0.1')
            ->setReferer('www.blubbergurken.io/blubbergurken-sale')
            ->setRevision('1.0.0');

        try {
            $findologicApi->{$requestType}();
            $this->fail('A ParamException was expected to occur when the shopurl parameter is missing.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param shopurl is not set.', $e->getMessage());
        }
    }

    /**
     * @dataProvider requestProvider
     * @param $requestType string
     */
    public function testRequiredParamUseripMissingWillThrowAnException($requestType)
    {
        $findologicApi = $this->getDefaultFindologicApi();

        $findologicApi
            ->setShopurl('www.blubbergurken.io')
            ->setReferer('www.blubbergurken.io/blubbergurken-sale')
            ->setRevision('1.0.0');

        try {
            $findologicApi->{$requestType}();
            $this->fail('A ParamException was expected to occur when the userip parameter is missing.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param userip is not set.', $e->getMessage());
        }
    }

    /**
     * @dataProvider requestProvider
     * @param $requestType string
     */
    public function testRequiredParamRefererMissingWillThrowAnException($requestType)
    {
        $findologicApi = $this->getDefaultFindologicApi();

        $findologicApi
            ->setShopurl('www.blubbergurken.io')
            ->setUserip('127.0.0.1')
            ->setRevision('1.0.0');

        try {
            $findologicApi->{$requestType}();
            $this->fail('A ParamException was expected to occur when the referer parameter is missing.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param referer is not set.', $e->getMessage());
        }
    }

    /**
     * @dataProvider requestProvider
     * @param $requestType string
     */
    public function testRequiredParamRevisionMissingWillThrowAnException($requestType)
    {
        $findologicApi = $this->getDefaultFindologicApi();

        $findologicApi
            ->setShopurl('www.blubbergurken.io')
            ->setUserip('127.0.0.1')
            ->setReferer('www.blubbergurken.io/blubbergurken-sale');

        try {
            $findologicApi->{$requestType}();
            $this->fail('A ParamException was expected to occur when the revision parameter is missing.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param revision is not set.', $e->getMessage());
        }
    }

    public function invalidShopkeyProvider()
    {
        return [
            'shopkey length not optimal' => ['INVALIDAF'],
            'shopkey contains invalid characters' => ['80AB18D4BE2654R78244106AD315DC2C'],
            'shopkey is lowercased' => ['80ab18d4be2654r78244106ad315dc2c'],
            'shopkey contains spaces' => ['80AB18D4BE2654A7 8244106AD315DC2C'],
            'shopkey contains special characters' => ['AAAAAA.AAAAAAÄAAAAAAAAAAAAAAAAA_'],
        ];
    }

    /**
     * @dataProvider invalidShopkeyProvider
     * @param $shopkey string
     */
    public function testExceptionIsThrownIfShopkeyIsInvalid($shopkey)
    {
        try {
            $findologicApi = new FindologicApi([
                FindologicApi::SHOPKEY => $shopkey
            ]);

            $this->fail('A ConfigException was expected to occur when the shopkey is invalid.');
        } catch (ConfigException $e) {
            $this->assertEquals('Shopkey format is invalid.', $e->getMessage());
        }
    }
}
