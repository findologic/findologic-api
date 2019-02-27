<?php

namespace FINDOLOGIC\Api\Tests;

use FINDOLOGIC\Api\FindologicApi;
use FINDOLOGIC\Api\FindologicConfig;
use FINDOLOGIC\Api\RequestBuilders\Json\SuggestionRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\NavigationRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\SearchRequestBuilder;

class FindologicApiTest extends TestBase
{
    /** @var array */
    private $validConfig = ['shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD'];

    /** @var FindologicApi */
    private $findologicApi;

    public function setUp()
    {
        parent::setUp();
        $this->findologicApi = new FindologicApi($this->validConfig);
    }

    public function testConfigCanBeSetAndGet()
    {
        $findologicApi = new FindologicApi($this->validConfig);
        $this->assertEquals(new FindologicConfig($this->validConfig), $findologicApi->getConfig());
    }

    public function testCreateSearchRequestWillReturnANewSearchRequestBuilder()
    {
        $searchRequestBuilder = $this->findologicApi->createSearchRequest();
        $this->assertInstanceOf(SearchRequestBuilder::class, $searchRequestBuilder);
    }

    public function testCreateNavigationRequestWillReturnANewNavigationRequestBuilder()
    {
        $navigationRequestBuilder = $this->findologicApi->createNavigationRequest();
        $this->assertInstanceOf(NavigationRequestBuilder::class, $navigationRequestBuilder);
    }

    public function testCreateSuggestionRequestWillReturnANewSuggestionRequestBuilder()
    {
        $suggestionRequestBuilder = $this->findologicApi->createSuggestionRequest();
        $this->assertInstanceOf(SuggestionRequestBuilder::class, $suggestionRequestBuilder);
    }
}
