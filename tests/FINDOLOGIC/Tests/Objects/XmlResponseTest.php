<?php

namespace FINDOLOGIC\Tests\Objects;

use FINDOLOGIC\Objects\XmlResponse;
use PHPUnit\Framework\TestCase;

class XmlResponseTest extends TestCase
{
    /**
     * Will use a real response that could come from a request. It returns the Object.
     *
     * @return XmlResponse
     */
    public function getDefaultRealResponseData()
    {
        // Get contents from a real response locally.
        $realResponseData = file_get_contents(__DIR__ . '/../../Mockdata/demoResponse.xml');
        return new XmlResponse($realResponseData);
    }

    public function testResponseWillReturnServersAsExpected()
    {
        $expectedFrontendServer = 'martell.frontend.findologic.com';
        $expectedBackendServer = 'hydra.backend.findologic.com';

        $response = $this->getDefaultRealResponseData();

        $this->assertEquals($expectedFrontendServer, $response->getServers()->getFrontend());
        $this->assertEquals($expectedBackendServer, $response->getServers()->getBackend());
    }

    public function testResponseWillReturnQueryAsExpected()
    {
        $expectedDidYouMeanQuery = 'ps4';
        $expectedSearchWordCount = 1;
        $expectedFoundWordsCount = 1;

        $response = $this->getDefaultRealResponseData();

        $this->assertEquals($expectedDidYouMeanQuery, $response->getQuery()->getDidYouMeanQuery());
        $this->assertEquals($expectedSearchWordCount, $response->getQuery()->getSearchedWordsCount());
        $this->assertEquals($expectedFoundWordsCount, $response->getQuery()->getFoundWordsCount());
    }

    public function testResponseWillReturnLimitAsExpected()
    {
        $expectedFirst = 0;
        $expectedCount = 24;

        $response = $this->getDefaultRealResponseData();

        $this->assertEquals($expectedFirst, $response->getQuery()->getLimit()->getFirst());
        $this->assertEquals($expectedCount, $response->getQuery()->getLimit()->getCount());
    }

    public function testResponseWillReturnQueryStringAsExpected()
    {
        $expectedValue = 'ps3';
        $expectedType = null;

        $response = $this->getDefaultRealResponseData();

        $this->assertEquals($expectedValue, $response->getQuery()->getQueryString()->getValue());
        $this->assertEquals($expectedType, $response->getQuery()->getQueryString()->getType());
    }

    public function testResponseWillReturnOriginalQueryAsExpected()
    {
        $expectedValue = 'original query';

        $response = $this->getDefaultRealResponseData();

        $this->assertEquals($expectedValue, $response->getQuery()->getOriginalQuery()->getValue());
        $this->assertTrue($response->getQuery()->getOriginalQuery()->getAllowOverride());
    }
}
