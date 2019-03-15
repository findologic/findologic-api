<?php

namespace FINDOLOGIC\Api\Tests\RequestBuilders\Json;

use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\Exceptions\ParamNotSetException;
use FINDOLOGIC\Api\RequestBuilders\Json\SuggestionRequestBuilder;
use FINDOLOGIC\Api\Tests\TestBase;

class SuggestionRequestBuilderTest extends TestBase
{
    use SuggestionDataProvider;

    /** @var Config */
    private $config;

    /** @var string */
    private $rawMockResponse;

    protected function setUp()
    {
        parent::setUp();
        $this->config = new Config([
            'shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD',
            'httpClient' => $this->httpClientMock,
        ]);
        $this->rawMockResponse = $this->getMockResponse('demoResponseSuggest.json');
    }

    public function testSendingRequestsWithoutRequiredParamsWillThrowAnException()
    {
        $suggestionRequestBuilder = new SuggestionRequestBuilder($this->config);
        try {
            $suggestionRequestBuilder->sendRequest();
            $this->fail('An exception was expected to happen if the shopurl param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param shopurl is not set.', $e->getMessage());
        }

        $suggestionRequestBuilder->setShopUrl('www.blubbergurken.io');
        try {
            $suggestionRequestBuilder->sendRequest();
            $this->fail('An exception was expected to happen if the query param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param query is not set.', $e->getMessage());
        }

        $this->httpClientMock->method('request')->willReturn($this->responseMock);
        $this->responseMock->method('getBody')->willReturn($this->streamMock);
        $this->responseMock->method('getStatusCode')->willReturn(200);
        $this->streamMock->method('getContents')
            ->willReturnOnConsecutiveCalls(
                $this->rawMockResponse,
                $this->rawMockResponse
            );

        $suggestionRequestBuilder->setQuery('something');
        $suggestionRequestBuilder->sendRequest();
    }

    /**
     * @dataProvider queryProvider
     * @param string $query
     * @param string $expectedResult
     */
    public function testSetQueryWillSetItInAValidFormat($query, $expectedResult)
    {
        $expectedParameter = sprintf('&query=%s', $expectedResult);

        $searchRequestBuilder = new SuggestionRequestBuilder($this->config);
        $this->setRequiredParamsForSuggestionRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setQuery($query);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidQueryProvider
     * @param mixed $invalidQuery
     */
    public function testSetQueryWillThrowAnExceptionWhenSubmittingInvalidQueries($invalidQuery)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter query is not valid.');

        $searchRequestBuilder = new SuggestionRequestBuilder($this->config);
        $this->setRequiredParamsForSuggestionRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setQuery($invalidQuery);
    }

    /**
     * @dataProvider autocompleteBlocksProvider
     * @param string $autocompleteBlock
     */
    public function testAddAutocompleteBlocksWillSetItInAValidFormat($autocompleteBlock)
    {
        $expectedParameter = sprintf('&autocompleteblocks=%s', $autocompleteBlock);

        $searchRequestBuilder = new SuggestionRequestBuilder($this->config);
        $this->setRequiredParamsForSuggestionRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addAutocompleteBlocks($autocompleteBlock);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidAutocompleteBlocksProvider
     * @param mixed $invalidAutocompleteblock
     */
    public function testInvalidShopurlWillThrowAnException($invalidAutocompleteblock)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter autocompleteblocks is not valid.');

        $searchRequestBuilder = new SuggestionRequestBuilder($this->config);
        $this->setRequiredParamsForSuggestionRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addAutocompleteBlocks($invalidAutocompleteblock);
    }

    /**
     * @dataProvider usergroupProvider
     * @param string $usergroup
     */
    public function testSetUsergroupHashWillSetItInAValidFormat($usergroup)
    {
        $expectedParameter = sprintf('&usergrouphash=%s', $usergroup);

        $searchRequestBuilder = new SuggestionRequestBuilder($this->config);
        $this->setRequiredParamsForSuggestionRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setUsergrouphash($usergroup);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidUsergroupProvider
     * @param mixed $invalidUsergroup
     */
    public function testInvalidUsergroupHashlWillThrowAnException($invalidUsergroup)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter usergrouphash is not valid.');

        $searchRequestBuilder = new SuggestionRequestBuilder($this->config);
        $this->setRequiredParamsForSuggestionRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setUsergrouphash($invalidUsergroup);
    }

    /**
     * @dataProvider multishopIdProvider
     * @param string $usergroup
     */
    public function testSetMultishopIdWillSetItInAValidFormat($usergroup)
    {
        $expectedParameter = sprintf('&multishop_id=%s', $usergroup);

        $searchRequestBuilder = new SuggestionRequestBuilder($this->config);
        $this->setRequiredParamsForSuggestionRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setMultishopId($usergroup);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidMultishopIdProvider
     * @param mixed $invalidMultishopId
     */
    public function testInvalidMultishopIdWillThrowAnException($invalidMultishopId)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter multishop_id is not valid.');

        $searchRequestBuilder = new SuggestionRequestBuilder($this->config);
        $this->setRequiredParamsForSuggestionRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setMultishopId($invalidMultishopId);
    }
}
