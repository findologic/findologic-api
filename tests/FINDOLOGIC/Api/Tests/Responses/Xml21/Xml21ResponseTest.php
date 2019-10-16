<?php

namespace FINDOLOGIC\Api\Tests\Responses\Xml21;

use FINDOLOGIC\Api\Responses\Xml21\Xml21Response;
use PHPUnit\Framework\TestCase;

class Xml21ResponseTest extends TestCase
{
    /**
     * Will use a real response that could come from a request. It returns the Object.
     *
     * @param string $filename
     * @return Xml21Response
     */
    public function getRealResponseData($filename = 'demoResponse.xml')
    {
        // Get contents from a real response locally.
        $realResponseData = file_get_contents(__DIR__ . '/../../../Mockdata/Xml21/' . $filename);
        return new Xml21Response($realResponseData);
    }

    public function testResponseWillReturnServersAsExpected()
    {
        $expectedFrontendServer = 'martell.frontend.findologic.com';
        $expectedBackendServer = 'hydra.backend.findologic.com';

        $response = $this->getRealResponseData();

        $this->assertSame($expectedFrontendServer, $response->getServers()->getFrontend());
        $this->assertSame($expectedBackendServer, $response->getServers()->getBackend());
    }

    public function testResponseWillReturnLandingpageAsExpected()
    {
        $expectedLink = 'https://www.landingpage.io/agb/';

        $response = $this->getRealResponseData();

        $this->assertSame($expectedLink, $response->getLandingPage()->getLink());
    }

    public function testResponseWillReturnPromotionAsExpected()
    {
        $expectedLink = 'https://promotion.com/';
        $expectedImage = 'https://promotion.com/promotion.png';

        $response = $this->getRealResponseData();

        $this->assertSame($expectedLink, $response->getPromotion()->getLink());
        $this->assertSame($expectedImage, $response->getPromotion()->getImage());
    }

    public function testResponseWillReturnResultsAsExpected()
    {
        $expectedCount = 1808;

        $response = $this->getRealResponseData();

        $this->assertSame($expectedCount, $response->getResults()->getCount());
    }

    public function testResponseWillReturnProductsAsExpected()
    {
        $expectedIds = ['019111105-37900', '029214085-37860'];
        $expectedRelevances = [1.3862943649292, 1.3862943649292];

        $response = $this->getRealResponseData();

        $count = 0;
        foreach ($response->getProducts() as $product) {
            $this->assertSame($expectedIds[$count], $product->getId());
            $this->assertSame($expectedRelevances[$count], $product->getRelevance());
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
                $this->assertSame($expectedName, $propertyName);
                $this->assertSame($expectedValue, $propertyValue);
            }
        }
    }

    public function testResponseWithoutLandingPageWillReturnNullWhenCallingIt()
    {
        $expectedLandingPage = null;
        $response = $this->getRealResponseData('demoResponseWithoutLandingPage.xml');
        $actualLandingPage = $response->getLandingPage();

        $this->assertSame($expectedLandingPage, $actualLandingPage);
    }

    public function testResponseWithoutPromotionWillReturnNullWhenCallingIt()
    {
        $expectedPromotion = null;
        $response = $this->getRealResponseData('demoResponseWithoutPromotion.xml');
        $actualPromotion = $response->getPromotion();

        $this->assertSame($expectedPromotion, $actualPromotion);
    }
}
