<?php

namespace FINDOLOGIC\Api\Tests\Responses\Xml20;

use FINDOLOGIC\Api\Requests\SearchNavigation\SearchRequest;
use FINDOLOGIC\Api\Responses\Response;
use FINDOLOGIC\Api\Responses\Xml20\Xml20Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class Xml20ResponseTest extends TestCase
{
    /**
     * Will use a real response that could come from a request. It returns the Object.
     *
     * @param string $filename
     * @return Xml20Response
     */
    public function getRealResponseData($filename = 'demoResponse.xml')
    {
        // Get contents from a real response locally.
        $realResponseData = file_get_contents(__DIR__ . '/../../../Mockdata/' . $filename);
        return new Xml20Response($realResponseData);
    }

    public function testResponseWillReturnServersAsExpected()
    {
        $expectedFrontendServer = 'martell.frontend.findologic.com';
        $expectedBackendServer = 'hydra.backend.findologic.com';

        $response = $this->getRealResponseData();

        $this->assertSame($expectedFrontendServer, $response->getServers()->getFrontend());
        $this->assertSame($expectedBackendServer, $response->getServers()->getBackend());
    }

    public function testResponseWillReturnQueryAsExpected()
    {
        $expectedAlternativeQuery = 'ps4';
        $expectedDidYouMeanQuery = 'ps4';
        $expectedSearchWordCount = 1;
        $expectedFoundWordsCount = 1;

        $response = $this->getRealResponseData();

        $this->assertSame($expectedDidYouMeanQuery, $response->getQuery()->getDidYouMeanQuery());
        $this->assertSame($expectedSearchWordCount, $response->getQuery()->getSearchedWordCount());
        $this->assertSame($expectedFoundWordsCount, $response->getQuery()->getFoundWordsCount());
        $this->assertSame($expectedAlternativeQuery, $response->getQuery()->getAlternativeQuery());
    }

    public function testResponseWillReturnAlternativeQueryAsExpectedIfDidYouMeanQueryIsNotSet()
    {
        $expectedAlternativeQuery = 'ps3';
        $response = $this->getRealResponseData('demoResponseWithoutDidYouMean.xml');
        $this->assertSame($expectedAlternativeQuery, $response->getQuery()->getAlternativeQuery());
    }

    public function testResponseWillReturnLimitAsExpected()
    {
        $expectedFirst = 0;
        $expectedCount = 24;

        $response = $this->getRealResponseData();

        $this->assertSame($expectedFirst, $response->getQuery()->getLimit()->getFirst());
        $this->assertSame($expectedCount, $response->getQuery()->getLimit()->getCount());
    }

    public function testResponseWillReturnQueryStringAsExpected()
    {
        $expectedValue = 'ps3';
        $expectedType = null;

        $response = $this->getRealResponseData();

        $this->assertSame($expectedValue, $response->getQuery()->getQueryString()->getValue());
        $this->assertSame($expectedType, $response->getQuery()->getQueryString()->getType());
    }

    public function testResponseWithTypeInQueryStringWillReturnOriginalQueryAsExpected()
    {
        $expectedValue = 'ps3';
        $expectedType = 'forced';

        $response = $this->getRealResponseData('demoResponseWithOriginalQueryType.xml');

        $this->assertSame($expectedValue, $response->getQuery()->getQueryString()->getValue());
        $this->assertSame($expectedType, $response->getQuery()->getQueryString()->getType());
    }

    public function testResponseWillReturnOriginalQueryAsExpected()
    {
        $expectedValue = 'original query';

        $response = $this->getRealResponseData();

        $this->assertSame($expectedValue, $response->getQuery()->getOriginalQuery()->getValue());
        $this->assertTrue($response->getQuery()->getOriginalQuery()->getAllowOverride());
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
        $expectedDirect = 0;
        $expectedIds = ['019111105-37900', '029214085-37860'];
        $expectedRelevances = [1.3862943649292, 1.3862943649292];

        $response = $this->getRealResponseData();

        $count = 0;
        foreach ($response->getProducts() as $product) {
            $this->assertSame($expectedIds[$count], $product->getId());
            $this->assertSame($expectedDirect, $product->getDirect());
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

    public function testResponseWillReturnFiltersAsExpected()
    {
        $expectedFilterDisplays = ['Preis', 'Farbe', 'Material', 'Hersteller', 'Kategorie'];
        $expectedFilterTypes = ['range-slider', 'color', 'select', 'select', 'select'];
        $expectedFilterNames = ['price', 'Farbe', 'Material', 'vendor', 'cat'];
        $expectedFilterSelects = ['single', 'multiselect', 'multiple', 'multiple', 'single'];
        $expectedFilterSelectedItems = [null, 1, null, null, null];
        $expectedFilterAmount = 5;

        $response = $this->getRealResponseData();

        $count = 0;
        foreach ($response->getFilters() as $filter) {
            $this->assertSame($expectedFilterDisplays[$count], $filter->getDisplay());
            $this->assertSame($expectedFilterTypes[$count], $filter->getType());
            $this->assertSame($expectedFilterNames[$count], $filter->getName());
            $this->assertSame($expectedFilterSelects[$count], $filter->getSelect());
            $this->assertSame($expectedFilterSelectedItems[$count], $filter->getSelectedItems());
            $this->assertSame($expectedFilterAmount, $response->getFilterCount());
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
                $this->assertSame($expectedSelectedRange, $filter->getAttributes()->getSelectedRange());
                $this->assertSame($expectedTotalRange, $filter->getAttributes()->getTotalRange());
                $this->assertSame($expectedStepSize, $filter->getAttributes()->getStepSize());
                $this->assertSame($expectedUnit, $filter->getAttributes()->getUnit());
            } else {
                // All other filter types do not have attributes.
                $this->assertSame(null, $filter->getAttributes());
            }
        }
    }

    public function testResponseWillReturnDisplaysOfItemsAsExpected()
    {
        // Usually items do not have a display or select, but documentation says differently.
        $expectedDisplays = [
            null, null, null, // Price
            null, null, null, // Color
            null, 'Leder', null, // Material
            null, null, null, // Vendor
            null, // Category
        ];

        $actualDisplays = [];
        $response = $this->getRealResponseData();
        if ($response->hasFilters() && $response->getFilterCount() > 0) {
            foreach ($response->getFilters() as $filter) {
                if ($filter->hasItems() && $filter->getItemAmount() > 0) {
                    foreach ($filter->getItems() as $item) {
                        $actualDisplays[] = $item->getDisplay();
                    }
                } else {
                    $this->fail('The demo response should have items.');
                }
            }
        } else {
            $this->fail('The demo response should have filters.');
        }

        $this->assertSame($expectedDisplays, $actualDisplays);
    }

    public function testResponseWillReturnSelectsOfItemsAsExpected()
    {
        // Usually items do not have a display or select, but documentation says differently.
        $expectedSelect = [
            null, null, null, // Price
            null, null, null, // Color
            null, null, null, // Material
            null, null, null, // Vendor
            null, // Category
        ];

        $actualSelect = [];
        $response = $this->getRealResponseData();
        if ($response->hasFilters() && $response->getFilterCount() > 0) {
            foreach ($response->getFilters() as $filter) {
                if ($filter->hasItems() && $filter->getItemAmount() > 0) {
                    foreach ($filter->getItems() as $item) {
                        $actualSelect[] = $item->getSelect();
                    }
                } else {
                    $this->fail('The demo response should have items.');
                }
            }
        } else {
            $this->fail('The demo response should have filters.');
        }

        $this->assertSame($expectedSelect, $actualSelect);
    }

    public function testResponseWillReturnWeightsOfItemsAsExpected()
    {
        // Weights do have a float value, but checking the value to its 1:1 value is unnecessary.
        $expectedWeight = [
            0.1, 0.1, 0.1, // Price
            0.1, 0.1, 0.1, // Color
            0.1, 0.1, 0.1, // Material
            0.1, 0.1, 0.1, // Vendor
            0.1, // Category
        ];

        $actualWeight = [];
        $response = $this->getRealResponseData();
        if ($response->hasFilters() && $response->getFilterCount() > 0) {
            foreach ($response->getFilters() as $filter) {
                if ($filter->hasItems() && $filter->getItemAmount() > 0) {
                    foreach ($filter->getItems() as $item) {
                        $actualWeight[] = $item->getWeight();
                    }
                } else {
                    $this->fail('The demo response should have items.');
                }
            }
        } else {
            $this->fail('The demo response should have filters.');
        }

        $this->assertEquals($expectedWeight, $actualWeight, '', 1);
    }

    public function testResponseWillReturnNamesOfItemsAsExpected()
    {
        $expectedNames = [
            '0.39 - 13.4', '13.45 - 25.99', '26 - 40.3', // Price
            'beige', 'blau', 'braun', // Color
            'Hartgepäck', 'Leder', 'Nylon', // Material
            'Bodenschatz', 'Braun Büffel', 'Camel Active', // Vendor
            'Buch', // Category
        ];

        $actualNames = [];
        $response = $this->getRealResponseData();
        if ($response->hasFilters() && $response->getFilterCount() > 0) {
            foreach ($response->getFilters() as $filter) {
                if ($filter->hasItems() && $filter->getItemAmount() > 0) {
                    foreach ($filter->getItems() as $item) {
                        $actualNames[] = $item->getName();
                    }
                } else {
                    $this->fail('The demo response should have items.');
                }
            }
        } else {
            $this->fail('The demo response should have filters.');
        }

        $this->assertSame($expectedNames, $actualNames);
    }

    public function testResponseWillReturnImagesOfItemsAsExpected()
    {
        $expectedImages = [
            null, null, null, // Price
            'https://blubbergurken.io/farbfilter/beige.gif', 'https://blubbergurken.io/farbfilter/blau.gif',
            'https://blubbergurken.io/farbfilter/braun.gif', // Color
            null, null, null, // Material
            null, null, null, // Vendor
            null, // Category
        ];

        $actualImages = [];
        $response = $this->getRealResponseData();
        if ($response->hasFilters() && $response->getFilterCount() > 0) {
            foreach ($response->getFilters() as $filter) {
                if ($filter->hasItems() && $filter->getItemAmount() > 0) {
                    foreach ($filter->getItems() as $item) {
                        $actualImages[] = $item->getImage();
                    }
                } else {
                    $this->fail('The demo response should have items.');
                }
            }
        } else {
            $this->fail('The demo response should have filters.');
        }

        $this->assertSame($expectedImages, $actualImages);
    }

    public function testResponseWillReturnColorsOfItemsAsExpected()
    {
        $expectedColors = [
            null, null, null, // Price
            '#F5F5DC', '#3c6380', '#94651e', // Color
            null, null, null, // Material
            null, null, null, // Vendor
            null, // Category
        ];

        $actualColors = [];
        $response = $this->getRealResponseData();
        if ($response->hasFilters() && $response->getFilterCount() > 0) {
            foreach ($response->getFilters() as $filter) {
                if ($filter->hasItems() && $filter->getItemAmount() > 0) {
                    foreach ($filter->getItems() as $item) {
                        $actualColors[] = $item->getColor();
                    }
                } else {
                    $this->fail('The demo response should have items.');
                }
            }
        } else {
            $this->fail('The demo response should have filters.');
        }

        $this->assertSame($expectedColors, $actualColors);
    }

    public function testResponseWillReturnFrequencyOfItemsAsExpected()
    {
        $expectedFrequency = [
            null, null, null, // Price
            null, null, null, // Color
            35, 1238, 110, // Material
            2, 77, 122, // Vendor
            5, // Category
        ];

        $actualFrequency = [];
        $response = $this->getRealResponseData();
        if ($response->hasFilters() && $response->getFilterCount() > 0) {
            foreach ($response->getFilters() as $filter) {
                if ($filter->hasItems() && $filter->getItemAmount() > 0) {
                    foreach ($filter->getItems() as $item) {
                        $actualFrequency[] = $item->getFrequency();
                    }
                } else {
                    $this->fail('The demo response should have items.');
                }
            }
        } else {
            $this->fail('The demo response should have filters.');
        }

        $this->assertSame($expectedFrequency, $actualFrequency);
    }

    public function testResponseWillReturnSubItemsOfItemsAsExpected()
    {
        $expectedSubItemDetails = [
            'display' => null,
            'select' => null,
            'weight' => 0.33799207210541, // Be very specific, so we can use assertSame.
            'name' => 'Beste Bücher',
            'image' => null,
            'color' => null,
            'frequency' => 5,
        ];

        $actualSubItemDetails = [];
        $response = $this->getRealResponseData();
        if ($response->hasFilters() && $response->getFilterCount() > 0) {
            foreach ($response->getFilters() as $filter) {
                if ($filter->hasItems() && $filter->getItemAmount() > 0) {
                    foreach ($filter->getItems() as $item) {
                        if ($item->getItems()) {
                            foreach ($item->getItems() as $subItem) {
                                $actualSubItemDetails['display'] = $subItem->getDisplay();
                                $actualSubItemDetails['select'] = $subItem->getSelect();
                                $actualSubItemDetails['weight'] = $subItem->getWeight();
                                $actualSubItemDetails['name'] = $subItem->getName();
                                $actualSubItemDetails['image'] = $subItem->getImage();
                                $actualSubItemDetails['color'] = $subItem->getColor();
                                $actualSubItemDetails['frequency'] = $subItem->getFrequency();
                            }
                        }
                    }
                } else {
                    $this->fail('The demo response should have items.');
                }
            }
        } else {
            $this->fail('The demo response should have filters.');
        }

        $this->assertSame($expectedSubItemDetails, $actualSubItemDetails);
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

    public function testResponseWithoutAllowOverrideWillReturnNullWhenCallingIt()
    {
        $expectedAllowOverride = null;
        $response = $this->getRealResponseData('demoResponseWithoutAllowOverride.xml');
        $actualAllowOverride = $response->getQuery()->getOriginalQuery()->getAllowOverride();

        $this->assertSame($expectedAllowOverride, $actualAllowOverride);
    }

    public function testResponseWithoutOriginalQueryWillReturnNullWhenCallingIt()
    {
        $expectedOriginalQuery = null;
        $response = $this->getRealResponseData('demoResponseWithoutOriginalQuery.xml');
        $actualOriginalQuery = $response->getQuery()->getOriginalQuery();

        $this->assertSame($expectedOriginalQuery, $actualOriginalQuery);
    }

    public function testUnknownResponseWillThrowAnException()
    {
        $expectedOutputAdapter = 'HTML_4.20';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Unknown or invalid outputAdapter "%s"', $expectedOutputAdapter));

        /** @var SearchRequest|PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->getMockBuilder(SearchRequest::class)
            ->disableOriginalConstructor()
            ->setMethods(['getOutputAdapter'])
            ->getMock();
        $request->expects($this->any())->method('getOutputAdapter')->willReturn($expectedOutputAdapter);

        Response::buildInstance($request, new \GuzzleHttp\Psr7\Response(), null, null);
    }
}
