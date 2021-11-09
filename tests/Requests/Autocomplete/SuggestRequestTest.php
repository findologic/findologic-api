<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests\Requests\Autocomplete;

use BadMethodCallException;
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

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new Config();
        $this->config
            ->setServiceId('ABCDABCDABCDABCDABCDABCDABCDABCD')
            ->setHttpClient($this->httpClientMock);

        $this->rawMockResponse = $this->getMockResponse('Autocomplete/demoResponseSuggest.json');
    }

    public function testSendingRequestsWithoutRequiredParamsWillThrowAnException(): void
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
    public function testSetQueryWillBeSetItInAValidFormat($expectedQuery): void
    {
        if ($expectedQuery === '') {
            $this->markTestSkipped('Empty queries are not allowed for suggest requests');
        }

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
     */
    public function testSetQueryWillThrowAnExceptionWhenSubmittingInvalidQueries(string $invalidQuery): void
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
    public function testAddAutocompleteBlocksWillBeSetItInAValidFormat($autocompleteBlock): void
    {
        $expectedParameter = 'autocompleteblocks';

        $searchRequest = new SuggestRequest();
        $this->setRequiredParamsForSuggestRequest($searchRequest);

        $searchRequest->addAutocompleteBlocks($autocompleteBlock);
        $params = $searchRequest->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertSame([$autocompleteBlock], $params[$expectedParameter]);
    }

    public function testMultipleAutocompleteBlocksCanBeAdded(): void
    {
        $expectedParameter = 'autocompleteblocks';

        $searchRequest = new SuggestRequest();
        $this->setRequiredParamsForSuggestRequest($searchRequest);

        $searchRequest->addAutocompleteBlocks('product');
        $searchRequest->addAutocompleteBlocks('ordernumber');
        $searchRequest->addAutocompleteBlocks('cat');

        $params = $searchRequest->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertSame([
            'product',
            'ordernumber',
            'cat'
        ], $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidAutocompleteBlocksProvider
     */
    public function testInvalidAutocompleteBlocksWillThrowAnException(string $invalidAutocompleteblock): void
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
    public function testSetUsergroupHashWillBeSetItInAValidFormat($usergroup): void
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
     * @dataProvider multishopIdProvider
     */
    public function testSetMultishopIdWillBeSetItInAValidFormat(int $multiShopId): void
    {
        $expectedParameter = 'multishop_id';

        $suggestRequest = new SuggestRequest();
        $this->setRequiredParamsForSuggestRequest($suggestRequest);

        $suggestRequest->setMultishopId($multiShopId);
        $params = $suggestRequest->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($multiShopId, $params[$expectedParameter]);
    }

    public function testGetBodyIsNotSupported(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Request body is not supported for suggest requests');

        $suggestRequest = new SuggestRequest();
        $suggestRequest->getBody();
    }
}
