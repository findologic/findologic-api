<?php

namespace FINDOLOGIC\Api\Tests\RequestBuilders\XmlResponse;

use FINDOLOGIC\Api\Exceptions\ParamNotSetException;
use FINDOLOGIC\Api\FindologicConfig;
use FINDOLOGIC\Api\RequestBuilders\XmlResponse\SearchRequestBuilder;
use FINDOLOGIC\Api\Tests\TestBase;

class SearchRequestBuilderTest extends TestBase
{
    /** @var FindologicConfig */
    private $findologicConfig;

    protected function setUp()
    {
        parent::setUp();
        $this->findologicConfig = new FindologicConfig([
            'shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD',
            'httpClient' => $this->httpClientMock,
        ]);
    }

    public function testSendingRequestsWithoutRequiredParamsWillThrowAnException()
    {
        $mockResponse = $this->getMockResponse('demoResponse.xml');
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
                $mockResponse,
                $mockResponse
            );

        $searchRequestBuilder->setQuery('');
        $searchRequestBuilder->sendRequest();
    }
}
