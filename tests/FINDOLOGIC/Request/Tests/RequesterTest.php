<?php

namespace FINDOLOGIC\Request\Tests;

use FINDOLOGIC\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Request\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class RequesterTest extends TestCase
{
    private $httpClientMock;
    private $httpResponseMock;

    public function setUp()
    {
        $this->httpClientMock = $this->createMock(Client::class);
        $this->httpResponseMock = $this->createMock(Response::class);

        $this->httpClientMock->method('request')
            ->willReturn($this->httpResponseMock);
    }

    public function requestTypeProvider()
    {
        return [
            [Request::TYPE_SEARCH],
            [Request::TYPE_NAVIGATION],
            [Request::TYPE_SUGGEST]
        ];
    }

    /**
     * @dataProvider requestTypeProvider
     * @param $requestType
     * @throws ServiceNotAliveException
     */
    public function testAlivetestIsSentWithPassedParams($requestType)
    {
        $expectedShopurl = 'www.blubbergurken24.io';
        $expectedAlivetestUrl = sprintf('https://api.blubbergurken.de/ps/%s/alivetest.php', $expectedShopurl);
        $expectedParams = '?shopkey=80AB18D4BE2654A78244106AD315DC2C';
        $expectedTimeout = 5;

        $this->httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET', ($expectedAlivetestUrl . $expectedParams), ['timeout' => $expectedTimeout]);

        $this->httpResponseMock->method('getStatusCode')
            ->willReturn(200);

        $this->httpResponseMock->method('getBody')
            ->willReturn('alive');

        $requester = Request::create($requestType);
        $requester->setShopkey('80AB18D4BE2654A78244106AD315DC2C')
            ->setReferer($expectedShopurl)
            ->setRevision('1.33.7')
            ->setShopurl('www.blubbergurken24.io')
            ->setUserip('127.0.0.1');

        $requester->send(
            'https://api.blubbergurken.de/ps/%s/%s',
            $expectedTimeout,
            $expectedTimeout,
            $this->httpClientMock
        );
    }

    /**
     * @dataProvider requestTypeProvider
     * @param $requestType
     * @expectedException \FINDOLOGIC\Exceptions\ServiceNotAliveException
     */
    public function testExceptionIsThrownIfServiceIsAliveButReturnsInvalidStatusCode($requestType)
    {
        $expectedShopurl = 'www.blubbergurken24.io';
        $expectedAlivetestUrl = sprintf('https://api.blubbergurken.de/ps/%s/alivetest.php', $expectedShopurl);
        $expectedParams = '?shopkey=80AB18D4BE2654A78244106AD315DC2C';
        $expectedTimeout = 5;

        $this->httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET', ($expectedAlivetestUrl . $expectedParams), ['timeout' => $expectedTimeout]);

        $this->httpResponseMock->method('getStatusCode')
            ->willReturn(500);

        $this->httpResponseMock->method('getBody')
            ->willReturn('alive');

        $requester = Request::create($requestType);
        $requester->setShopkey('80AB18D4BE2654A78244106AD315DC2C')
            ->setReferer($expectedShopurl)
            ->setRevision('1.33.7')
            ->setShopurl('www.blubbergurken24.io')
            ->setUserip('127.0.0.1');

        $requester->send(
            'https://api.blubbergurken.de/ps/%s/%s',
            $expectedTimeout,
            $expectedTimeout,
            $this->httpClientMock
        );
    }

    /**
     * @dataProvider requestTypeProvider
     * @param $requestType
     * @expectedException \FINDOLOGIC\Exceptions\ServiceNotAliveException
     */
    public function testExceptionIsThrownIfServiceIsNotAliveButReturnsValidStatusCode($requestType)
    {
        $expectedShopurl = 'www.blubbergurken24.io';
        $expectedAlivetestUrl = sprintf('https://api.blubbergurken.de/ps/%s/alivetest.php', $expectedShopurl);
        $expectedParams = '?shopkey=80AB18D4BE2654A78244106AD315DC2C';
        $expectedTimeout = 5;

        $this->httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET', ($expectedAlivetestUrl . $expectedParams), ['timeout' => $expectedTimeout]);

        $this->httpResponseMock->method('getStatusCode')
            ->willReturn(200);

        $this->httpResponseMock->method('getBody')
            ->willReturn('definitely not alive.');

        $requester = Request::create($requestType);
        $requester->setShopkey('80AB18D4BE2654A78244106AD315DC2C')
            ->setReferer($expectedShopurl)
            ->setRevision('1.33.7')
            ->setShopurl('www.blubbergurken24.io')
            ->setUserip('127.0.0.1');

        $requester->send(
            'https://api.blubbergurken.de/ps/%s/%s',
            $expectedTimeout,
            $expectedTimeout,
            $this->httpClientMock
        );
    }

    /**
     * @dataProvider requestTypeProvider
     * @param $requestType
     * @expectedException \FINDOLOGIC\Exceptions\ServiceNotAliveException
     */
    public function testExceptionIsThrownIfServiceIsNotAliveAndReturnsInvalidStatusCode($requestType)
    {
        $expectedShopurl = 'www.blubbergurken24.io';
        $expectedAlivetestUrl = sprintf('https://api.blubbergurken.de/ps/%s/alivetest.php', $expectedShopurl);
        $expectedParams = '?shopkey=80AB18D4BE2654A78244106AD315DC2C';
        $expectedTimeout = 5;

        $this->httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET', ($expectedAlivetestUrl . $expectedParams), ['timeout' => $expectedTimeout]);

        $this->httpResponseMock->method('getStatusCode')
            ->willReturn(404);

        $this->httpResponseMock->method('getBody')
            ->willReturn('definitely not alive.');

        $requester = Request::create($requestType);
        $requester->setShopkey('80AB18D4BE2654A78244106AD315DC2C')
            ->setReferer($expectedShopurl)
            ->setRevision('1.33.7')
            ->setShopurl('www.blubbergurken24.io')
            ->setUserip('127.0.0.1');

        $requester->send(
            'https://api.blubbergurken.de/ps/%s/%s',
            $expectedTimeout,
            $expectedTimeout,
            $this->httpClientMock
        );
    }
}
