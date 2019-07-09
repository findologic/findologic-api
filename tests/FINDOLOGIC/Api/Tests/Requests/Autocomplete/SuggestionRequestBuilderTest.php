<?php

namespace FINDOLOGIC\Api\Tests\RequestBuilders\Autocomplete;

use FINDOLOGIC\Api\Client;
use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\Exceptions\ParamNotSetException;
use FINDOLOGIC\Api\Requests\Autocomplete\SuggestRequest;
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
        $this->config = new Config();
        $this->config
            ->setServiceId('ABCDABCDABCDABCDABCDABCDABCDABCD')
            ->setHttpClient($this->httpClientMock);

        $this->rawMockResponse = $this->getMockResponse('demoResponseSuggest.json');
    }

    public function testSendingRequestsWithoutRequiredParamsWillThrowAnException()
    {
        $suggestionRequestBuilder = new SuggestRequest();
        $client = new Client($this->config);
        try {
            $client->send($suggestionRequestBuilder);
            $this->fail('An exception was expected to happen if the shopurl param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param shopurl is not set.', $e->getMessage());
        }

        $suggestionRequestBuilder->setShopUrl('www.blubbergurken.io');
        try {
            $client->send($suggestionRequestBuilder);
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
        $client->send($suggestionRequestBuilder);
    }

    /**
     * @dataProvider queryProvider
     * @param string $expectedQuery
     */
    public function testSetQueryWillBeSetItInAValidFormat($expectedQuery)
    {
        $expectedParameter = 'query';

        $searchRequestBuilder = new SuggestRequest();
        $this->setRequiredParamsForSuggestionRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setQuery($expectedQuery);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($expectedQuery, $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidQueryProvider
     * @param mixed $invalidQuery
     */
    public function testSetQueryWillThrowAnExceptionWhenSubmittingInvalidQueries($invalidQuery)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter query is not valid.');

        $searchRequestBuilder = new SuggestRequest();
        $this->setRequiredParamsForSuggestionRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setQuery($invalidQuery);
    }

    /**
     * @dataProvider autocompleteBlocksProvider
     * @param string $autocompleteBlock
     */
    public function testAddAutocompleteBlocksWillBeSetItInAValidFormat($autocompleteBlock)
    {
        $expectedParameter = 'autocompleteblocks';

        $searchRequestBuilder = new SuggestRequest();
        $this->setRequiredParamsForSuggestionRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addAutocompleteBlocks($autocompleteBlock);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($autocompleteBlock, $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidAutocompleteBlocksProvider
     * @param mixed $invalidAutocompleteblock
     */
    public function testInvalidAutocompleteBlocksWillThrowAnException($invalidAutocompleteblock)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter autocompleteblocks is not valid.');

        $searchRequestBuilder = new SuggestRequest();
        $this->setRequiredParamsForSuggestionRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addAutocompleteBlocks($invalidAutocompleteblock);
    }

    /**
     * @dataProvider usergroupProvider
     * @param string $usergroup
     */
    public function testSetUsergroupHashWillBeSetItInAValidFormat($usergroup)
    {
        $expectedParameter = 'usergrouphash';

        $searchRequestBuilder = new SuggestRequest();
        $this->setRequiredParamsForSuggestionRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setUsergrouphash($usergroup);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($usergroup, $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidUsergroupProvider
     * @param mixed $invalidUsergroup
     */
    public function testInvalidUsergroupHashWillThrowAnException($invalidUsergroup)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter usergrouphash is not valid.');

        $searchRequestBuilder = new SuggestRequest();
        $this->setRequiredParamsForSuggestionRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setUsergrouphash($invalidUsergroup);
    }

    /**
     * @dataProvider multishopIdProvider
     * @param string $usergroup
     */
    public function testSetMultishopIdWillBeSetItInAValidFormat($usergroup)
    {
        $expectedParameter = 'multishop_id';

        $searchRequestBuilder = new SuggestRequest();
        $this->setRequiredParamsForSuggestionRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setMultishopId($usergroup);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($usergroup, $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidMultishopIdProvider
     * @param mixed $invalidMultishopId
     */
    public function testInvalidMultishopIdWillThrowAnException($invalidMultishopId)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter multishop_id is not valid.');

        $searchRequestBuilder = new SuggestRequest();
        $this->setRequiredParamsForSuggestionRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setMultishopId($invalidMultishopId);
    }
}
