<?php

namespace FINDOLOGIC\Api\Tests;

use FINDOLOGIC\Api\Client;
use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Api\Requests\AlivetestRequest;
use FINDOLOGIC\Api\Requests\Autocomplete\SuggestRequest;
use FINDOLOGIC\Api\Requests\SearchNavigation\SearchRequest;
use FINDOLOGIC\Api\Responses\Autocomplete\SuggestResponse;
use FINDOLOGIC\Api\Responses\Html\GenericHtmlResponse;
use FINDOLOGIC\Api\Responses\Xml20\Xml20Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;

class ClientTest extends TestBase
{
    /** @var string */
    private $validShopkey = 'ABCDABCDABCDABCDABCDABCDABCDABCD';

    /** @var Config */
    private $config;

    /** @var SearchRequest */
    private $requestBuilder;

    protected function setUp()
    {
        parent::setUp();

        $this->config = new Config();
        $this->config
            ->setServiceId($this->validShopkey)
            ->setHttpClient($this->httpClientMock);
        $this->requestBuilder = new SuggestRequest();

        $this->requestBuilder
            ->setShopUrl('blubbergurken.de')
            ->setQuery('blubbergurken');
    }

    /**
     * @param string $expectedAlivetestUrl
     * @param string $expectedRequestUrl
     * @param string $expectedAlivetestBody
     * @param string $expectedSearchResultBody
     */
    private function setExpectationsForAlivetestRequestsWithASearch(
        $expectedAlivetestUrl,
        $expectedRequestUrl,
        $expectedAlivetestBody,
        $expectedSearchResultBody
    ) {
        $this->httpClientMock->method('request')
            ->withConsecutive(
                ['GET', $expectedAlivetestUrl, ['connect_timeout' => 1.0]],
                ['GET', $expectedRequestUrl, ['connect_timeout' => 3.0]]
            )
            ->willReturnOnConsecutiveCalls($this->responseMock, $this->responseMock);
        $this->responseMock->method('getBody')
            ->with()
            ->willReturnOnConsecutiveCalls($this->streamMock, $this->streamMock);
        $this->responseMock->method('getStatusCode')
            ->with()
            ->willReturnOnConsecutiveCalls(200);
        $this->streamMock->method('getContents')
            ->with()
            ->willReturnOnConsecutiveCalls($expectedAlivetestBody, $expectedSearchResultBody);
    }

    public function testRequestIsBeingCalledWithExpectedParameters()
    {
        $requestParams = http_build_query([
            'shopurl' => 'blubbergurken.de',
            'query' => 'blubbergurken',
            'shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD',
        ]);

        $expectedRequestUrl = 'https://service.findologic.com/ps/blubbergurken.de/autocomplete.php?' . $requestParams;

        $responseBody = '[]';
        $expectedResult = [];

        $this->setExpectationsForRequests($expectedRequestUrl, $responseBody);

        $client = new Client($this->config);

        /** @var SuggestResponse $suggestResponse */
        $suggestResponse = $client->send($this->requestBuilder);

        $this->assertSame($expectedResult, $suggestResponse->getSuggestions());
    }

    public function badStatusCodeProvider()
    {
        return [
            [500],
            [501],
            [502],
            [503],
            [504],
            [505],
            [400],
            [401],
            [402],
            [403],
            [404],
            [405],
            [406],
            [300],
            [201],
            [102],
        ];
    }

    /**
     * @dataProvider badStatusCodeProvider
     * @param int $statusCode
     */
    public function testRequestWillThrowAnExceptionIfItHasAnUnexpectedStatusCode($statusCode)
    {
        $requestParams = http_build_query([
            'shopurl' => 'blubbergurken.de',
            'query' => 'blubbergurken',
            'shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD',
        ]);

        $expectedRequestUrl = 'https://service.findologic.com/ps/blubbergurken.de/autocomplete.php?' . $requestParams;

        $expectedBody = json_encode([
            'label' => 'schuljahr',
            'block' => 'suggest',
            'frequency' => '3463',
            'imageUrl' => null,
            'price' => null,
            'identifier' => null,
            'basePrice' => null,
            'basePriceUnit' => null,
            'url' => null,
        ]);

        $this->setExpectationsForRequests($expectedRequestUrl, $expectedBody, $statusCode);

        $client = new Client($this->config);

        try {
            $client->send($this->requestBuilder);
            $this->fail('An ServiceNotAliveException should be thrown if the status code is not OK.');
        } catch (ServiceNotAliveException $e) {
            $this->assertEquals(sprintf(
                'The service is not alive. Reason: Unexpected status code %s.',
                $statusCode
            ), $e->getMessage());
        }
    }

    public function testAliveTestRequestWillBeCalledWithLessTimeout()
    {
        $requestParams = http_build_query([
            'query' => 'blubbergurken',
            'shopurl' => 'blubbergurken.de',
            'userip' => '127.0.0.1',
            'referer' => 'https://www.google.at/?query=blubbergurken',
            'revision' => '1.0.0',
            'shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD',
        ]);

        $expectedAlivetestUrl = 'https://service.findologic.com/ps/blubbergurken.de/alivetest.php?' . $requestParams;
        $expectedRequestUrl = 'https://service.findologic.com/ps/blubbergurken.de/index.php?' . $requestParams;

        $expectedAlivetestBody = 'alive';
        $expectedSearchResultBody = $this->getMockResponse('demoResponse.xml');

        $this->setExpectationsForAlivetestRequestsWithASearch(
            $expectedAlivetestUrl,
            $expectedRequestUrl,
            $expectedAlivetestBody,
            $expectedSearchResultBody
        );

        $searchRequestBuilder = new SearchRequest();
        $searchRequestBuilder
            ->setQuery('blubbergurken')
            ->setShopUrl('blubbergurken.de')
            ->setUserIp('127.0.0.1')
            ->setReferer('https://www.google.at/?query=blubbergurken')
            ->setRevision('1.0.0');

        $client = new Client($this->config);

        /** @var Xml20Response $xmlResponse */
        $xmlResponse = $client->send($searchRequestBuilder);

        // Local response time should be fast since the data will not be sent to another server, but instead it
        // will be directly read from the ram.
        $this->assertEquals(0, $xmlResponse->getResponseTime(), '', 0.01);
    }

    public function badAliveTestBodies()
    {
        return [
            [' alive'],
            ['alive '],
            ['alive\n'],
            ['\nalive'],
            ['it could be alive'],
            ['the service is alive'],
            ['i am more dead than death himself, so lets say I am alive'],
            ['rip'],
            ['i am ded'],
            ['<h1>alive</h1>'],
            ['<span>alive</span>'],
        ];
    }

    /**
     * @dataProvider badAliveTestBodies
     * @param string $expectedBody
     */
    public function testExceptionIsThrownIfAliveTestBodyIsSomethingElseThenAlive($expectedBody)
    {
        $this->expectException(ServiceNotAliveException::class);
        $this->expectExceptionMessage(sprintf('The service is not alive. Reason: %s', $expectedBody));

        $requestParams = http_build_query([
            'query' => 'blubbergurken',
            'shopurl' => 'blubbergurken.de',
            'userip' => '127.0.0.1',
            'referer' => 'https://www.google.at/?query=blubbergurken',
            'revision' => '1.0.0',
            'shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD',
        ]);

        $expectedRequestUrl = 'https://service.findologic.com/ps/blubbergurken.de/index.php?' . $requestParams;
        $expectedAlivetestUrl = 'https://service.findologic.com/ps/blubbergurken.de/alivetest.php?' . $requestParams;

        $this->setExpectationsForAliveTestRequests($expectedRequestUrl, $expectedAlivetestUrl, '', $expectedBody);

        $searchRequestBuilder = new SearchRequest();
        $searchRequestBuilder
            ->setQuery('blubbergurken')
            ->setShopUrl('blubbergurken.de')
            ->setUserIp('127.0.0.1')
            ->setReferer('https://www.google.at/?query=blubbergurken')
            ->setRevision('1.0.0');

        $client = new Client($this->config);
        $client->send($searchRequestBuilder);
    }

    public function testWhenGuzzleFailsWillThrowAnException()
    {
        $expectedExceptionMessage = 'Guzzle is dying. Maybe it can be saved with a heart massage.';
        $this->expectException(ServiceNotAliveException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $requestParams = http_build_query([
            'query' => 'blubbergurken',
            'shopurl' => 'blubbergurken.de',
            'userip' => '127.0.0.1',
            'referer' => 'https://www.google.at/?query=blubbergurken',
            'revision' => '1.0.0',
            'shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD',
        ]);

        $expectedAlivetestUrl = 'https://service.findologic.com/ps/blubbergurken.de/alivetest.php?' . $requestParams;

        $this->httpClientMock->method('request')
            ->with('GET', $expectedAlivetestUrl, ['connect_timeout' => 1.0])
            ->willThrowException(new RequestException(
                $expectedExceptionMessage,
                new Request('GET', $expectedAlivetestUrl)
            ));

        $searchRequestBuilder = new SearchRequest();
        $searchRequestBuilder
            ->setQuery('blubbergurken')
            ->setShopUrl('blubbergurken.de')
            ->setUserIp('127.0.0.1')
            ->setReferer('https://www.google.at/?query=blubbergurken')
            ->setRevision('1.0.0');

        $client = new Client($this->config);
        $client->send($searchRequestBuilder);
    }

    /**
     * We are already covered due to our type safety, but you should not be able to make a request with for example
     * an alivetest request, since that one also extends from the RequestBuilder object.
     */
    public function testInvalidParameterBuilderWillThrowAnException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Unknown request builder: %s',
            AlivetestRequest::class
        ));

        $requestParams = http_build_query([
            'shopurl' => 'blubbergurken.de',
            'shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD',
        ]);

        $expectedAlivetestUrl = 'https://service.findologic.com/ps/blubbergurken.de/alivetest.php?' . $requestParams;

        $this->httpClientMock->method('request')
            ->with('GET', $expectedAlivetestUrl, ['connect_timeout' => 1.0])
            ->willReturnOnConsecutiveCalls($this->responseMock);
        $this->responseMock->method('getBody')
            ->with()
            ->willReturnOnConsecutiveCalls($this->streamMock, $this->streamMock);
        $this->responseMock->method('getStatusCode')
            ->with()
            ->willReturnOnConsecutiveCalls(200);
        $this->streamMock->method('getContents')
            ->with()
            ->willReturnOnConsecutiveCalls('alive');

        $client = new Client($this->config);
        $alivetestRequest = new AlivetestRequest();
        $alivetestRequest->setShopUrl('blubbergurken.de');

        $client->send($alivetestRequest);
    }

    public function testHtmlOutputAdapterWillReturnHtmlResponse()
    {
        $requestParams = http_build_query([
            'query' => 'blubbergurken',
            'shopurl' => 'blubbergurken.de',
            'userip' => '127.0.0.1',
            'referer' => 'https://www.google.at/?query=blubbergurken',
            'revision' => '1.0.0',
            'outputAdapter' => 'HTML_3.1',
            'shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD',
        ]);

        $expectedRequestUrl = 'https://service.findologic.com/ps/blubbergurken.de/index.php?' . $requestParams;
        $expectedAlivetestUrl = 'https://service.findologic.com/ps/blubbergurken.de/alivetest.php?' . $requestParams;

        $this->setExpectationsForAliveTestRequests(
            $expectedRequestUrl,
            $expectedAlivetestUrl,
            $this->getMockResponse('demoResponse.html')
        );

        $searchRequestBuilder = new SearchRequest();
        $searchRequestBuilder
            ->setQuery('blubbergurken')
            ->setShopUrl('blubbergurken.de')
            ->setUserIp('127.0.0.1')
            ->setReferer('https://www.google.at/?query=blubbergurken')
            ->setRevision('1.0.0')
            ->setOutputAdapter('HTML_3.1');

        $this->config->setHttpClient($this->httpClientMock);
        $client = new Client($this->config);
        $response = $client->send($searchRequestBuilder);

        $this->assertInstanceOf(GenericHtmlResponse::class, $response);
    }
}
