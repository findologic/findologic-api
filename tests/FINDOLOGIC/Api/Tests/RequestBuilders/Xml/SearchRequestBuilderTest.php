<?php

namespace FINDOLOGIC\Api\Tests\RequestBuilders\Xml;

use FINDOLOGIC\Api\Exceptions\InvalidParamException;
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

    public function stringProvider()
    {
        return [
            'some random string can be set' => [
                'string' => 'something',
                'expectedResult' => 'something',
            ],
            'an empty string an be set' => [
                'string' => '',
                'expectedResult' => '',
            ],
            'special characters in string should be url encoded' => [
                'string' => '/ /',
                'expectedResult' => '%2F+%2F',
            ],
        ];
    }

    /**
     * @dataProvider stringProvider
     * @param string $string
     * @param string $expectedResult
     */
    public function testSetQueryCanBeSetAndIsFormattedAsQueryString($string, $expectedResult)
    {
        $expectedParameter = sprintf('&query=%s', $expectedResult);

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setQuery($string);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    public function invalidQueryProvider()
    {
        return [
            'integer as query' => [
                'query' => 21,
            ],
            'object as query' => [
                'query' => new \stdClass(),
            ],
            'float as query' => [
                'query' => 3.1415,
            ],
        ];
    }

    /**
     * @dataProvider invalidQueryProvider
     * @param mixed $query
     */
    public function testSetQueryWillThrowAnExceptionWhenSubmittingInvalidQueries($query)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter query is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setQuery($query);
    }


}
