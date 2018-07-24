<?php

namespace FINDOLOGIC\Request\Tests;

use FINDOLOGIC\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Helpers\FindologicClient;
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

        $this->httpClientMock->expects($this->at(0))
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

        $this->httpClientMock->expects($this->at(0))
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

        $this->httpClientMock->expects($this->at(0))
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

        $this->httpClientMock->expects($this->at(0))
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

    public function requestTypeAndActionProvider()
    {
        return [
            [Request::TYPE_SEARCH, FindologicClient::SEARCH_ACTION],
            [Request::TYPE_NAVIGATION, FindologicClient::NAVIGATION_ACTION],
            [Request::TYPE_SUGGEST, FindologicClient::SUGGEST_ACTION]
        ];
    }

    /**
     * @dataProvider requestTypeAndActionProvider
     * @param $requestType
     * @throws ServiceNotAliveException
     */
    public function testRequestingWillReturnTheSearchResult($requestType, $action)
    {
        $expectedResultBody = <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<searchResult>
    <servers>
        <frontend>some.frontend.findologic.com</frontend>
        <backend>someother.backend.findologic.com</backend>
    </servers>
    <query>
        <limit first="0" count="1" />
        <queryString>blubbergurken</queryString>
        <searchedWordCount>1</searchedWordCount>
        <foundWordsCount>1</foundWordsCount>
    </query>
    <results>
        <count>20</count>
    </results>
    <products>
        <product id="281" relevance="1.3862943649292" direct="0" />
    </products>
    <filters>
        <filter>
            <name>Color</name>
            <display>Color</display>
            <select>multiselect</select>
            <selectedItems>0</selectedItems>
            <type>color</type>
            <items>
                <item>
                    <name>beige</name>
                    <weight>0.11161217838526</weight>
                    <image>https://www.blubbergurken.io/media/colorfilter/beige.png</image>
                    <color>#F5F5DC</color>
                </item>
            </items>
        </filter>
    </filters>
</searchResult>
EOL;
        $expectedShopurl = 'www.blubbergurken24.io';
        $expectedAlivetestUrl = sprintf('https://api.blubbergurken.de/ps/%s/alivetest.php', $expectedShopurl);
        $expectedAlivetestParams = '?shopkey=80AB18D4BE2654A78244106AD315DC2C';
        $expectedRequestUrl = sprintf('https://api.blubbergurken.de/ps/%s/%s', $expectedShopurl, $action);
        $expectedParams = '?shopkey=80AB18D4BE2654A78244106AD315DC2C&referer=www.blubbergurken24.io&revision=1.33.7' .
            '&shopurl=www.blubbergurken24.io&userip=127.0.0.1';

        $expectedTimeout = 5;

        // Alivetest
        $this->httpClientMock->expects($this->at(0))
            ->method('request')
            ->with('GET', ($expectedAlivetestUrl . $expectedAlivetestParams), ['timeout' => $expectedTimeout]);

        // Request
        $this->httpClientMock->expects($this->at(1))
            ->method('request')
            ->with('GET', ($expectedRequestUrl . $expectedParams), ['timeout' => $expectedTimeout]);

        // TODO: Return the response on actual request to test the raw response.
        $this->httpResponseMock->method('getBody')
            ->willReturn('alive');

        $this->httpResponseMock->method('getStatusCode')
            ->willReturn(200);

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

    //TODO: Add tests that should fail if not all required fields are set.
}
