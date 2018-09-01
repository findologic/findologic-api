<?php

namespace FINDOLOGIC\Tests\Objects;

use FINDOLOGIC\Objects\XmlResponse;
use PHPUnit\Framework\TestCase;

class XmlResponseTest extends TestCase
{
    /**
     * Will use a real response that could come from a request. It returns the Object.
     *
     * @param string $filename
     * @return XmlResponse
     */
    public function getRealResponseData($filename = 'demoResponse.xml')
    {
        // Get contents from a real response locally.
        $realResponseData = file_get_contents(__DIR__ . '/../../Mockdata/' . $filename);
        return new XmlResponse($realResponseData);
    }

    public function testResponseWillReturnServersAsExpected()
    {
        $expectedFrontendServer = 'martell.frontend.findologic.com';
        $expectedBackendServer = 'hydra.backend.findologic.com';

        $response = $this->getRealResponseData();

        $this->assertEquals($expectedFrontendServer, $response->getServers()->getFrontend());
        $this->assertEquals($expectedBackendServer, $response->getServers()->getBackend());
    }

    public function testResponseWillReturnQueryAsExpected()
    {
        $expectedDidYouMeanQuery = 'ps4';
        $expectedSearchWordCount = 1;
        $expectedFoundWordsCount = 1;

        $response = $this->getRealResponseData();

        $this->assertEquals($expectedDidYouMeanQuery, $response->getQuery()->getDidYouMeanQuery());
        $this->assertEquals($expectedSearchWordCount, $response->getQuery()->getSearchedWordsCount());
        $this->assertEquals($expectedFoundWordsCount, $response->getQuery()->getFoundWordsCount());
    }

    public function testResponseWillReturnLimitAsExpected()
    {
        $expectedFirst = 0;
        $expectedCount = 24;

        $response = $this->getRealResponseData();

        $this->assertEquals($expectedFirst, $response->getQuery()->getLimit()->getFirst());
        $this->assertEquals($expectedCount, $response->getQuery()->getLimit()->getCount());
    }

    public function testResponseWillReturnQueryStringAsExpected()
    {
        $expectedValue = 'ps3';
        $expectedType = '';

        $response = $this->getRealResponseData();

        $this->assertEquals($expectedValue, $response->getQuery()->getQueryString()->getValue());
        $this->assertEquals($expectedType, $response->getQuery()->getQueryString()->getType());
    }

    public function testResponseWithTypeInQueryStringWillReturnOriginalQueryAsExpected()
    {
        $expectedValue = 'ps3';
        $expectedType = 'forced';

        $response = $this->getRealResponseData('demoResponseWithOriginalQueryType.xml');

        $this->assertEquals($expectedValue, $response->getQuery()->getQueryString()->getValue());
        $this->assertEquals($expectedType, $response->getQuery()->getQueryString()->getType());
    }

    public function testResponseWillReturnOriginalQueryAsExpected()
    {
        $expectedValue = 'original query';

        $response = $this->getRealResponseData();

        $this->assertEquals($expectedValue, $response->getQuery()->getOriginalQuery()->getValue());
        $this->assertTrue($response->getQuery()->getOriginalQuery()->getAllowOverride());
    }

    public function testResponseWillReturnLandingpageAsExpected()
    {
        $expectedLink = 'https://www.landingpage.io/agb/';

        $response = $this->getRealResponseData();

        $this->assertEquals($expectedLink, $response->getLandingPage()->getLink());
    }

    public function testResponseWillReturnPromotionAsExpected()
    {
        $expectedLink = 'https://promotion.com/';
        $expectedImage = 'https://promotion.com/promotion.png';

        $response = $this->getRealResponseData();

        $this->assertEquals($expectedLink, $response->getPromotion()->getLink());
        $this->assertEquals($expectedImage, $response->getPromotion()->getImage());
    }

    public function testResponseWillReturnResultsAsExpected()
    {
        $expectedCount = 1808;

        $response = $this->getRealResponseData();

        $this->assertEquals($expectedCount, $response->getResults()->getCount());
    }

    public function testResponseWillReturnProductsAsExpected()
    {
        $expectedDirect = 0;
        $expectedIds = ['019111105-37900', '029214085-37860'];
        $expectedRelevances = [1.3862943649292, 1.3862943649292];

        $response = $this->getRealResponseData();

        $count = 0;
        foreach ($response->getProducts() as $product) {
            $this->assertEquals($expectedIds[$count], $product->getId());
            $this->assertEquals($expectedDirect, $product->getDirect());
            $this->assertEquals($expectedRelevances[$count], $product->getRelevance());
            $count++;
        }
    }

    public function testResponseWillReturnPropertiesAsExpected()
    {
        $expectedName = 'ordernumber';
        $expectedValue = '019111105-37900';

        $response = $this->getRealResponseData();

        foreach ($response->getProducts() as $product) {
            foreach ($product->getProperties() as $propertyName => $propertyValue) {
                $this->assertEquals($expectedName, $propertyName);
                $this->assertEquals($expectedValue, $propertyValue);
            }
        }
    }
}
