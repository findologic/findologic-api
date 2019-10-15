<?php

namespace FINDOLOGIC\Api\Tests\Requests\Autocomplete;

use FINDOLOGIC\Api\Client;
use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\Exceptions\ParamNotSetException;
use FINDOLOGIC\Api\Requests\Autocomplete\SuggestRequest;
use FINDOLOGIC\Api\Tests\TestBase;

class SuggestRequestTest extends TestBase
{
    use SuggestDataProvider;

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
        $suggestRequest = new SuggestRequest();
        $client = new Client($this->config);
        try {
            $client->send($suggestRequest);
            $this->fail('An exception was expected to happen if the shopurl param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param shopurl is not set.', $e->getMessage());
        }

        $suggestRequest->setShopUrl('www.blubbergurken.io');
        try {
            $client->send($suggestRequest);
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

        $suggestRequest->setQuery('something');
        $client->send($suggestRequest);
    }

    /**
     * @dataProvider queryProvider
     * @param string $expectedQuery
     */
    public function testSetQueryWillBeSetItInAValidFormat($expectedQuery)
    {
        $expectedParameter = 'query';

        $suggestRequest = new SuggestRequest();
        $this->setRequiredParamsForSuggestRequest($suggestRequest);

        $suggestRequest->setQuery($expectedQuery);
        $params = $suggestRequest->getParams();
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

        $searchRequest = new SuggestRequest();
        $this->setRequiredParamsForSuggestRequest($searchRequest);

        $searchRequest->setQuery($invalidQuery);
    }

    /**
     * @dataProvider autocompleteBlocksProvider
     * @param string $autocompleteBlock
     */
    public function testAddAutocompleteBlocksWillBeSetItInAValidFormat($autocompleteBlock)
    {
        $expectedParameter = 'autocompleteblocks';

        $searchRequest = new SuggestRequest();
        $this->setRequiredParamsForSuggestRequest($searchRequest);

        $searchRequest->addAutocompleteBlocks($autocompleteBlock);
        $params = $searchRequest->getParams();
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

        $suggestRequest = new SuggestRequest();
        $this->setRequiredParamsForSuggestRequest($suggestRequest);

        $suggestRequest->addAutocompleteBlocks($invalidAutocompleteblock);
    }

    /**
     * @dataProvider usergroupProvider
     * @param string $usergroup
     */
    public function testSetUsergroupHashWillBeSetItInAValidFormat($usergroup)
    {
        $expectedParameter = 'usergrouphash';

        $suggestRequest = new SuggestRequest();
        $this->setRequiredParamsForSuggestRequest($suggestRequest);

        $suggestRequest->setUsergrouphash($usergroup);
        $params = $suggestRequest->getParams();
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

        $suggestRequest = new SuggestRequest();
        $this->setRequiredParamsForSuggestRequest($suggestRequest);

        $suggestRequest->setUsergrouphash($invalidUsergroup);
    }

    /**
     * @dataProvider multishopIdProvider
     * @param string $usergroup
     */
    public function testSetMultishopIdWillBeSetItInAValidFormat($usergroup)
    {
        $expectedParameter = 'multishop_id';

        $suggestRequest = new SuggestRequest();
        $this->setRequiredParamsForSuggestRequest($suggestRequest);

        $suggestRequest->setMultishopId($usergroup);
        $params = $suggestRequest->getParams();
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

        $suggestRequest = new SuggestRequest();
        $this->setRequiredParamsForSuggestRequest($suggestRequest);

        $suggestRequest->setMultishopId($invalidMultishopId);
    }
}
