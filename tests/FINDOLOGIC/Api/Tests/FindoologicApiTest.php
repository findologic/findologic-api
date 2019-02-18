<?php

namespace FINDOLOGIC\Api\Tests;

use FINDOLOGIC\Api\FindologicApi;
use FINDOLOGIC\Api\FindologicConfig;
use FINDOLOGIC\Api\RequestBuilders\JsonResponse\SuggestionRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\XmlResponse\NavigationRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\XmlResponse\SearchRequestBuilder;
use PHPUnit\Framework\TestCase;

class FindoologicApiTest extends TestCase
{
    /** @var array */
    private $validConfig = ['shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD'];

    /** @var FindologicApi */
    private $findologicApi;

    /** @var FindologicConfig */
    private $findologicConfig;

    public function setUp()
    {
        parent::setUp();
        $this->findologicApi = new FindologicApi($this->validConfig);
        $this->findologicConfig = new FindologicConfig($this->validConfig);
    }

    public function testConfigCanBeSetAndGet()
    {
        $findologicApi = new FindologicApi($this->validConfig);
        $this->assertEquals(new FindologicConfig($this->validConfig), $findologicApi->getConfig());
    }

    public function testCreateSearchRequestWillReturnANewSearchRequestBuilder()
    {
        $searchRequestBuilder = $this->findologicApi->createSearchRequest();
        $this->assertEquals(new SearchRequestBuilder($this->findologicConfig), $searchRequestBuilder);
    }

    public function testCreateNavigationRequestWillReturnANewNavigationRequestBuilder()
    {
        $navigationRequestBuilder = $this->findologicApi->createNavigationRequest();
        $this->assertEquals(new NavigationRequestBuilder($this->findologicConfig), $navigationRequestBuilder);
    }

    public function testCreateSuggestionRequestWillReturnANewSuggestionRequestBuilder()
    {
        $SuggestionRequestBuilder = $this->findologicApi->createSuggestionRequest();
        $this->assertEquals(new SuggestionRequestBuilder($this->findologicConfig), $SuggestionRequestBuilder);
    }
}
