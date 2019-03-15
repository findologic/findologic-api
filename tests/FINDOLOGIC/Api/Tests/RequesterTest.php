<?php

namespace FINDOLOGIC\Api\Tests;

use FINDOLOGIC\Api\Requester;
use FINDOLOGIC\Api\RequestBuilders\Json\SuggestionRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\NavigationRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\SearchRequestBuilder;
use InvalidArgumentException;

class RequesterTest extends TestBase
{
    /** @var array */
    private $validConfig = ['shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD'];

    public function testCreateSearchRequestWillReturnANewSearchRequestBuilder()
    {
        $searchRequestBuilder = Requester::getRequestBuilder(0, $this->validConfig);
        $this->assertInstanceOf(SearchRequestBuilder::class, $searchRequestBuilder);
    }

    public function testCreateNavigationRequestWillReturnANewNavigationRequestBuilder()
    {
        $navigationRequestBuilder = Requester::getRequestBuilder(1, $this->validConfig);
        $this->assertInstanceOf(NavigationRequestBuilder::class, $navigationRequestBuilder);
    }

    public function testCreateSuggestionRequestWillReturnANewSuggestionRequestBuilder()
    {
        $suggestionRequestBuilder = Requester::getRequestBuilder(2, $this->validConfig);
        $this->assertInstanceOf(SuggestionRequestBuilder::class, $suggestionRequestBuilder);
    }

    public function testExceptionWillBeThrownIfRequestBuilderIsUnknown()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown request type.');
        Requester::getRequestBuilder(3, $this->validConfig);
    }
}
