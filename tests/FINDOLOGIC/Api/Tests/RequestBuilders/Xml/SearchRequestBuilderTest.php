<?php

namespace FINDOLOGIC\Api\Tests\RequestBuilders\Xml;

use FINDOLOGIC\Api\Exceptions\ParamNotSetException;
use FINDOLOGIC\Api\FindologicConfig;
use FINDOLOGIC\Api\RequestBuilders\Xml\SearchRequestBuilder;
use FINDOLOGIC\Api\Tests\TestBase;

class SearchRequestBuilderTest extends TestBase
{
    /** @var FindologicConfig */
    private $findologicConfig;

    /** @var string */
    private $mockResponse;

    /** @var string */
    private $serviceUrl = 'https://service.findologic.com/ps/blubbergurken.io';

    /** @var string */
    private $alivetestEndpoint = 'alivetest.php';

    /** @var string */
    private $searchEndpoint = 'index.php';

    protected function setUp()
    {
        parent::setUp();
        $this->findologicConfig = new FindologicConfig([
            'shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD',
            'httpClient' => $this->httpClientMock,
        ]);
        $this->mockResponse = $this->getMockResponse('demoResponse.xml');
    }

    public function testSendingRequestsWithoutRequiredParamsWillThrowAnException()
    {
        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        try {
            $searchRequestBuilder->sendRequest();
            $this->fail('An exception was expected to happen if the shopurl param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param shopurl is not set.', $e->getMessage());
        }

        $searchRequestBuilder->setShopurl('blubbergurken.io');
        try {
            $searchRequestBuilder->sendRequest();
            $this->fail('An exception was expected to happen if the userip param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param userip is not set.', $e->getMessage());
        }

        $searchRequestBuilder->setUserip('127.0.0.1');
        try {
            $searchRequestBuilder->sendRequest();
            $this->fail('An exception was expected to happen if the referer param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param referer is not set.', $e->getMessage());
        }

        $searchRequestBuilder->setReferer('https://blubbergurken.io/blubbergurken-sale/');
        try {
            $searchRequestBuilder->sendRequest();
            $this->fail('An exception was expected to happen if the revision param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param revision is not set.', $e->getMessage());
        }

        $searchRequestBuilder->setRevision('2.5.10');
        try {
            $searchRequestBuilder->sendRequest();
            $this->fail('An exception was expected to happen if the query param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param query is not set.', $e->getMessage());
        }

        $this->httpClientMock->method('request')->willReturn($this->responseMock);
        $this->responseMock->method('getBody')->willReturn($this->streamMock);
        $this->responseMock->method('getStatusCode')->willReturn(200);
        $this->streamMock->method('getContents')
            ->willReturnOnConsecutiveCalls(
                'alive',
                'alive',
                $this->mockResponse,
                $this->mockResponse
            );

        $searchRequestBuilder->setQuery('');
        $searchRequestBuilder->sendRequest();
    }

    public function testRequiredParamsWillBeSetAsExpected()
    {
        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $this->httpClientMock->method('request')
            ->withConsecutive(
                // Alivetest request.
                [
                    'GET',
                    sprintf(
                        '%s/%s?%s',
                        $this->serviceUrl,
                        $this->alivetestEndpoint,
                        http_build_query([
                            'shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD'
                        ])
                    ),
                    ['connect_timeout' => 1.0]
                ],
                // Search request.
                [
                    'GET',
                    sprintf(
                        '%s/%s?%s',
                        $this->serviceUrl,
                        $this->searchEndpoint,
                        http_build_query([
                            'shopurl' => 'blubbergurken.io',
                            'userip' => '127.0.0.1',
                            'referer' => 'https://blubbergurken.io/blubbergurken-sale/',
                            'revision' => '2.5.10',
                            'query' => '',
                            'shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD',
                        ])
                    ),
                    ['connect_timeout' => 3.0]
                ]
            )
            ->willReturn($this->responseMock);
        $this->responseMock->method('getBody')->willReturn($this->streamMock);
        $this->responseMock->method('getStatusCode')->willReturn(200);
        $this->streamMock->method('getContents')
            ->willReturnOnConsecutiveCalls(
                'alive',
                'alive',
                $this->mockResponse,
                $this->mockResponse
            );

        $searchRequestBuilder->setQuery('');
        $searchRequestBuilder->sendRequest();
    }
}
