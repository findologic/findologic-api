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

    public function testResponseWillReturnFiltersAsExpected()
    {
        $expectedFilterDisplays = ['Preis', 'Farbe', 'Material', 'Hersteller', 'Kategorie'];
        $expectedFilterTypes = ['range-slider', 'color', 'select', 'select', 'select'];
        $expectedFilterNames = ['price', 'Farbe', 'Material', 'vendor', 'cat'];
        $expectedFilterSelects = ['single', 'multiselect', 'multiple', 'multiple', 'single'];
        $expectedFilterSelectedItems = [0, 1, 0, 0, 0];

        $response = $this->getRealResponseData();

        $count = 0;
        foreach ($response->getFilters() as $filter) {
            $this->assertEquals($expectedFilterDisplays[$count], $filter->getDisplay());
            $this->assertEquals($expectedFilterTypes[$count], $filter->getType());
            $this->assertEquals($expectedFilterNames[$count], $filter->getName());
            $this->assertEquals($expectedFilterSelects[$count], $filter->getSelect());
            $this->assertEquals($expectedFilterSelectedItems[$count], $filter->getSelectedItems());
            $count++;
        }
    }

    public function testResponseWillReturnAttributesAsExpected()
    {
        $expectedSelectedRange = ['min' => 0.39, 'max' => 2239.10];
        // There is no difference between selected and total if the result was not filtered by a range-slider.
        $expectedTotalRange = ['min' => 0.39, 'max' => 2239.10];
        $expectedStepSize = 0.1;
        $expectedUnit = '€';

        $response = $this->getRealResponseData();

        foreach ($response->getFilters() as $filter) {
            // Only range-slider does have attributes.
            if ($filter->getType() === 'range-slider') {
                $this->assertEquals($expectedSelectedRange, $filter->getAttributes()->getSelectedRange());
                $this->assertEquals($expectedTotalRange, $filter->getAttributes()->getTotalRange());
                $this->assertEquals($expectedStepSize, $filter->getAttributes()->getStepSize());
                $this->assertEquals($expectedUnit, $filter->getAttributes()->getUnit());
            } else {
                // All other filter types do not have attributes.
                $this->assertEquals(null, $filter->getAttributes());
            }
        }
    }

    public function testResponseWillReturnItemsAsExpected()
    {
        // Usually items do not have a display or select, but documentation says differently.
        $expectedDisplays = [
            '', '', '', // Price
            '', '', '', // Color
            '', 'Leder', '', // Material
            '', '', '', // Vendor
            '', // Category
        ];

        $expectedSelect = [
            '', '', '', // Price
            '', '', '', // Color
            '', '', '', // Material
            '', '', '', // Vendor
            '', // Category
        ];

        // Weights do have a float value, but checking the value to its 1:1 value is unnecessary.
        $expectedWeight = [
            0.1, 0.1, 0.1, // Price
            0.1, 0.1, 0.1, // Color
            0.1, 0.1, 0.1, // Material
            0.1, 0.1, 0.1, // Vendor
            0.1, // Category
        ];

        $expectedNames = [
            '0.39 - 13.4', '13.45 - 25.99', '26 - 40.3', // Price
            'beige', 'blau', 'braun', // Color
            'Hartgepäck', 'Leder', 'Nylon', // Material
            'Bodenschatz', 'Braun Büffel', 'Camel Active', // Vendor
            'Buch', // Category
        ];

        $expectedImages = [
            '', '', '', // Price
            'https://blubbergurken.io/farbfilter/beige.gif', 'https://blubbergurken.io/farbfilter/blau.gif',
            'https://blubbergurken.io/farbfilter/braun.gif', // Color
            '', '', '', // Material
            '', '', '', // Vendor
            '', // Category
        ];

        $expectedColors = [
            '', '', '', // Price
            '#F5F5DC', '#3c6380', '#94651e', // Color
            '', '', '', // Material
            '', '', '', // Vendor
            '', // Category
        ];

        $expectedFrequency = [
            0, 0, 0, // Price
            0, 0, 0, // Color
            35, 1238, 110, // Material
            2, 77, 122, // Vendor
            5, // Category
        ];

        $expectedSubItemDetails = [
            'display' => '',
            'select' => '',
            'weight' => 0.1,
            'name' => 'Beste Bücher',
            'image' => '',
            'color' => '',
            'frequency' => 5,
        ];

        $response = $this->getRealResponseData();

        $count = 0;
        foreach ($response->getFilters() as $filter) {
            if (count($filter->getItems()) > 0) {
                foreach ($filter->getItems() as $item) {
                    $this->assertEquals($expectedDisplays[$count], $item->getDisplay());
                    $this->assertEquals($expectedSelect[$count], $item->getSelect());
                    $this->assertEquals($expectedWeight[$count], $item->getWeight(), '', 1);
                    $this->assertEquals($expectedNames[$count], $item->getName());
                    $this->assertEquals($expectedImages[$count], $item->getImage());
                    $this->assertEquals($expectedColors[$count], $item->getColor());
                    $this->assertEquals($expectedFrequency[$count], $item->getFrequency());
                    // For subcategories.
                    if ($item->getItems()) {
                        foreach ($item->getItems() as $subItem) {
                            $this->assertEquals($expectedSubItemDetails['display'], $subItem->getDisplay());
                            $this->assertEquals($expectedSubItemDetails['select'], $subItem->getSelect());
                            $this->assertEquals(
                                $expectedSubItemDetails['weight'],
                                $subItem->getWeight(),
                                '',
                                1
                            );
                            $this->assertEquals($expectedSubItemDetails['name'], $subItem->getName());
                            $this->assertEquals($expectedSubItemDetails['image'], $subItem->getImage());
                            $this->assertEquals($expectedSubItemDetails['color'], $subItem->getColor());
                            $this->assertEquals($expectedSubItemDetails['frequency'], $subItem->getFrequency());
                        }
                    }
                    $count++;
                }
            }
        }
    }

    public function testResponseWithoutLandingPageWillReturnNullWhenCallingIt()
    {
        $expectedLandingPage = null;
        $response = $this->getRealResponseData('demoResponseWithoutLandingPage.xml');
        $landingpage = $response->getLandingPage();

        $this->assertEquals($expectedLandingPage, $landingpage);
    }

    public function testResponseWithoutPromotionWillReturnNullWhenCallingIt()
    {
        $expectedPromotion = null;
        $response = $this->getRealResponseData('demoResponseWithoutPromotion.xml');
        $promotion = $response->getPromotion();

        $this->assertEquals($expectedPromotion, $promotion);
    }

    public function testResponseWithoutAllowOverrideWillReturnNullWhenCallingIt()
    {
        $expectedAllowOverride = null;
        $response = $this->getRealResponseData('demoResponseWithoutAllowOverride.xml');
        $allowOverride = $response->getQuery()->getOriginalQuery()->getAllowOverride();

        $this->assertEquals($expectedAllowOverride, $allowOverride);
    }
}
