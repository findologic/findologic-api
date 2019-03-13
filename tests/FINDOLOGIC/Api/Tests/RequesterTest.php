<?php

namespace FINDOLOGIC\Api\Tests;

use FINDOLOGIC\Api\Requester;
use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\RequestBuilders\Json\SuggestionRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\NavigationRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\SearchRequestBuilder;
use http\Client\Request;

class RequesterTest extends TestBase
{
    /** @var array */
    private $validConfig = ['shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD'];

    /** @var Requester */
    private $findologicApi;

    public function setUp()
    {
        parent::setUp();
    }

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
}
