<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests\Responses\Xml21;

use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\CategoryFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\ColorPickerFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Item\CategoryItem;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Item\ColorItem;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Item\RangeSliderItem;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Item\VendorImageItem;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\LabelTextFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\RangeSliderFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\SelectDropdownFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\VendorImageFilter;
use FINDOLOGIC\Api\Responses\Xml21\Xml21Response;
use PHPUnit\Framework\TestCase;

class Xml21ResponseTest extends TestCase
{
    /**
     * Will use a real response that could come from a request. It returns the Object.
     *
     * @param string $filename
     *
     * @return Xml21Response
     */
    public function getRealResponseData($filename = 'demoResponse.xml')
    {
        // Get contents from a real response locally.
        $realResponseData = file_get_contents(__DIR__ . '/../../Mockdata/Xml21/' . $filename);

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

    public function testResponseWillReturnQueryAsExpected()
    {
        $expectedAlternativeQuery = 'ps4';
        $expectedDidYouMeanQuery = 'ps4';

        $response = $this->getRealResponseData();

        $this->assertSame($expectedDidYouMeanQuery, $response->getQuery()->getDidYouMeanQuery());
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

        $response = $this->getRealResponseData('demoResponseWithoutDidYouMean.xml');

        $this->assertSame($expectedValue, $response->getQuery()->getQueryString()->getValue());
        $this->assertNull($response->getQuery()->getQueryString()->getType());
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

    public function testResponseWithoutAllowOverrideWillReturnNullWhenCallingIt()
    {
        $response = $this->getRealResponseData('demoResponseWithoutAllowOverride.xml');
        $actualAllowOverride = $response->getQuery()->getOriginalQuery()->getAllowOverride();

        $this->assertFalse($actualAllowOverride);
    }

    public function testResponseWithoutOriginalQueryWillReturnNullWhenCallingIt()
    {
        $response = $this->getRealResponseData('demoResponseWithoutOriginalQuery.xml');
        $actualOriginalQuery = $response->getQuery()->getOriginalQuery();

        $this->assertNull($actualOriginalQuery);
    }

    public function testResponseWillReturnLandingpageAsExpected()
    {
        $expectedLink = 'https://www.landingpage.io/agb/';

        $response = $this->getRealResponseData();

        $this->assertSame($expectedLink, $response->getLandingPage()->getLink());
    }

    public function testResponseWithoutLandingPageWillReturnNullWhenCallingIt()
    {
        $response = $this->getRealResponseData('demoResponseWithoutLandingPage.xml');
        $actualLandingPage = $response->getLandingPage();

        $this->assertNull($actualLandingPage);
    }

    public function testResponseWillReturnPromotionAsExpected()
    {
        $expectedLink = 'https://promotion.com/';
        $expectedImage = 'https://promotion.com/promotion.png';

        $response = $this->getRealResponseData();

        $this->assertSame($expectedLink, $response->getPromotion()->getLink());
        $this->assertSame($expectedImage, $response->getPromotion()->getImage());
    }

    public function testResponseWithoutPromotionWillReturnNullWhenCallingIt()
    {
        $response = $this->getRealResponseData('demoResponseWithoutPromotion.xml');
        $actualPromotion = $response->getPromotion();

        $this->assertNull($actualPromotion);
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

    public function testResponseWillReturnMainFiltersAsExpected()
    {
        $expectedFilterItemCounts = [null];
        $expectedFilterCssClasses = [null];
        $expectedNoAvailableFiltersTexts = [null];
        $expectedFilterNames = ['price'];
        $expectedFilterDisplays = ['Preis'];
        $expectedFilterSelects = ['single'];
        $expectedSelectedItemCount = [0];
        $expectedFilterTypes = [RangeSliderFilter::class];
        $expectedFilterCount = 1;

        $response = $this->getRealResponseData();

        $count = 0;
        foreach ($response->getMainFilters() as $filter) {
            $this->assertSame($expectedFilterItemCounts[$count], $filter->getItemCount());
            $this->assertSame($expectedFilterCssClasses[$count], $filter->getCssClass());
            $this->assertSame($expectedNoAvailableFiltersTexts[$count], $filter->getNoAvailableFiltersText());
            $this->assertSame($expectedFilterDisplays[$count], $filter->getDisplay());
            $this->assertSame($expectedFilterNames[$count], $filter->getName());
            $this->assertInstanceOf($expectedFilterTypes[$count], $filter);
            $this->assertSame($expectedFilterSelects[$count], $filter->getSelect());
            $this->assertSame($expectedSelectedItemCount[$count], $filter->getSelectedItemCount());
            $this->assertSame($expectedFilterCount, $response->getMainFilterCount());
            $count++;
        }
    }

    public function testResponseWillReturnOtherFiltersAsExpected()
    {
        $expectedFilterItemCounts = [null, 2, 0, -2, -2, -2];
        $expectedFilterCssClasses = [null, 'fl-material', null, null, null, null];
        $expectedNoAvailableFiltersTexts = [null, null, 'Keine Hersteller', null, null, null];
        $expectedFilterNames = ['Farbe', 'Material', 'vendor', 'cat', 'image', 'label'];
        $expectedFilterDisplays = ['Farbe', 'Material', 'Hersteller', 'Kategorie', 'Vendor Image', 'Label'];
        $expectedFilterSelects = ['multiselect', 'multiple', 'multiple', 'single', 'single', 'single'];
        $expectedSelectedItemCount = [1, 0, 0, 0, 0, 0];
        $expectedSelectedItems = [ColorItem::class, null, null, null, null, null];
        $expectedFilterTypes = [
            ColorPickerFilter::class,
            SelectDropdownFilter::class,
            SelectDropdownFilter::class,
            CategoryFilter::class,
            VendorImageFilter::class,
            LabelTextFilter::class
        ];
        $expectedFilterCount = 6;

        $response = $this->getRealResponseData('demoResponseWithAllFilters.xml');

        $count = 0;
        foreach ($response->getOtherFilters() as $filter) {
            $this->assertSame($expectedFilterItemCounts[$count], $filter->getItemCount());
            $this->assertSame($expectedFilterCssClasses[$count], $filter->getCssClass());
            $this->assertSame($expectedNoAvailableFiltersTexts[$count], $filter->getNoAvailableFiltersText());
            $this->assertSame($expectedFilterDisplays[$count], $filter->getDisplay());
            $this->assertSame($expectedFilterNames[$count], $filter->getName());
            $this->assertInstanceOf($expectedFilterTypes[$count], $filter);
            $this->assertSame($expectedFilterSelects[$count], $filter->getSelect());
            $this->assertSame($expectedSelectedItemCount[$count], $filter->getSelectedItemCount());
            $this->assertSame($expectedFilterCount, $response->getOtherFilterCount());
            foreach ($filter->getSelectedItems() as $selectedItem) {
                $this->assertInstanceOf($expectedSelectedItems[$count], $selectedItem);
            }
            $count++;
        }
    }

    public function testResponseWillReturnAttributesAsExpected()
    {
        $expectedAttributesStepSizes = [0.1];
        $expectedAttributesUnits = ['€'];

        $response = $this->getRealResponseData();

        $count = 0;
        foreach ($response->getMainFilters() as $filter) {
            if ($filter->getAttributes()) {
                $attributes = $filter->getAttributes();
                $this->assertSame($expectedAttributesStepSizes[$count], $attributes->getStepSize());
                $this->assertSame($expectedAttributesUnits[$count], $attributes->getUnit());
            } else {
                $this->fail('The demo response should have attributes.');
            }
            $count++;
        }
    }

    public function testResponseWillReturnAttributeRangeAsExpected()
    {
        $expectedAttributeSelectedRangesMin = [0.39];
        $expectedAttributeSelectedRangesMax = [2239.1];
        $expectedAttributeTotalRangesMin = [0.39];
        $expectedAttributeTotalRangesMax = [2239.1];

        $response = $this->getRealResponseData();

        $count = 0;
        foreach ($response->getMainFilters() as $filter) {
            if ($filter->getAttributes()) {
                $selectedRange = $filter->getAttributes()->getSelectedRange();
                $totalRange = $filter->getAttributes()->getTotalRange();
                $this->assertSame($expectedAttributeSelectedRangesMin[$count], $selectedRange->getMin());
                $this->assertSame($expectedAttributeSelectedRangesMax[$count], $selectedRange->getMax());
                $this->assertSame($expectedAttributeTotalRangesMin[$count], $totalRange->getMin());
                $this->assertSame($expectedAttributeTotalRangesMax[$count], $selectedRange->getMax());
            } else {
                $this->fail('The demo response should have attributes.');
            }
            $count++;
        }
    }

    public function testResponseWillReturnWeightsOfItemsAsExpected()
    {
        // Weights do have a float value, but checking the value to its 1:1 value is unnecessary.
        $expectedWeight = [
            0.10730088502169, 0.3296460211277, 0.90265488624573, // Farbe
            0.10730088502179, // Image
            0.038716815412045, 0.63053095340729, 0.12168141454458, // Material
            0.0022123893722892, 0.08517698943615, 0.13495574891567, // Hersteller
            0.25156819820404, // Kategorie
        ];

        $actualWeight = [];
        $response = $this->getRealResponseData();
        if ($response->hasOtherFilters() && $response->getOtherFilterCount() > 0) {
            foreach ($response->getOtherFilters() as $filter) {
                if (count($filter->getItems()) > 0) {
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
            'beige', 'blau', 'braun', // Farbe
            'image1', // Image
            'Hartgepäck', 'Leder', 'Nylon', // Material
            'Bodenschatz', 'Braun Büffel', 'Camel Active', // Hersteller
            'Buch', // Kategorie
        ];

        $actualNames = [];
        $response = $this->getRealResponseData();
        if ($response->hasOtherFilters() && $response->getOtherFilterCount() > 0) {
            foreach ($response->getOtherFilters() as $filter) {
                if (count($filter->getItems()) > 0) {
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

        $this->assertEquals($expectedNames, $actualNames);
    }

    public function testResponseWillReturnColorImagesOfItemsAsExpected()
    {
        $expectedImages = [
            'https://blubbergurken.io/farbfilter/beige.gif',
            'https://blubbergurken.io/farbfilter/blau.gif',
            'https://blubbergurken.io/farbfilter/braun.gif'
        ];

        $actualImages = [];
        $response = $this->getRealResponseData();
        if ($response->hasOtherFilters() && $response->getOtherFilterCount() > 0) {
            foreach ($response->getOtherFilters() as $filter) {
                if (count($filter->getItems()) > 0) {
                    foreach ($filter->getItems() as $item) {
                        if ($item instanceof ColorItem) {
                            $actualImages[] = $item->getImage();
                        }
                    }
                } else {
                    $this->fail('The demo response should have items.');
                }
            }
        } else {
            $this->fail('The demo response should have filters.');
        }

        $this->assertEquals($expectedImages, $actualImages);
    }

    public function testResponseWillReturnImagesOfItemsAsExpected()
    {
        $expectedImages = [
            'https://blubbergurken.io/farbfilter/image1.gif'
        ];

        $actualImages = [];
        $response = $this->getRealResponseData();
        if ($response->hasOtherFilters() && $response->getOtherFilterCount() > 0) {
            foreach ($response->getOtherFilters() as $filter) {
                if (count($filter->getItems()) > 0) {
                    foreach ($filter->getItems() as $item) {
                        if ($item instanceof VendorImageItem) {
                            $actualImages[] = $item->getImage();
                        }
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
            '#F5F5DC', '#3c6380', '#94651e'
        ];

        $actualColors = [];
        $response = $this->getRealResponseData();
        if ($response->hasOtherFilters() && $response->getOtherFilterCount() > 0) {
            foreach ($response->getOtherFilters() as $filter) {
                if (count($filter->getItems()) > 0) {
                    foreach ($filter->getItems() as $item) {
                        if ($item instanceof ColorItem) {
                            $actualColors[] = $item->getColor();
                        }
                    }
                } else {
                    $this->fail('The demo response should have items.');
                }
            }
        } else {
            $this->fail('The demo response should have filters.');
        }

        $this->assertEquals($expectedColors, $actualColors);
    }

    public function testResponseWillReturnFrequencyOfItemsAsExpected()
    {
        $expectedFrequencies = [
            null, null, null, // Farbe
            null, // Image
            35, 1238, 110, // Material
            2, 77, 122, // Hersteller
            5, // Kategorie
        ];

        $actualFrequencies = [];
        $response = $this->getRealResponseData();
        if ($response->hasOtherFilters() && $response->getOtherFilterCount() > 0) {
            foreach ($response->getOtherFilters() as $filter) {
                if (count($filter->getItems()) > 0) {
                    foreach ($filter->getItems() as $item) {
                        $actualFrequencies[] = $item->getFrequency();
                    }
                } else {
                    $this->fail('The demo response should have items.');
                }
            }
        } else {
            $this->fail('The demo response should have filters.');
        }

        $this->assertEquals($expectedFrequencies, $actualFrequencies);
    }

    public function testResponseWillReturnSelectedOfItemsAsExpected()
    {
        $expectedSelected = [
            false, true, false, // Farbe
            false, // Image
            false, false, false, // Material
            false, false, false, // Hersteller
            false, // Kategorie
        ];

        $actualSelected = [];
        $response = $this->getRealResponseData();
        if ($response->hasOtherFilters() && $response->getOtherFilterCount() > 0) {
            foreach ($response->getOtherFilters() as $filter) {
                if (count($filter->getItems()) > 0) {
                    foreach ($filter->getItems() as $item) {
                        $actualSelected[] = $item->isSelected();
                    }
                } else {
                    $this->fail('The demo response should have items.');
                }
            }
        } else {
            $this->fail('The demo response should have filters.');
        }

        $this->assertEquals($expectedSelected, $actualSelected);
    }

    public function testResponseWillReturnParametersOfItemsAsExpected()
    {
        $expectedMin = [
            0.39,
            13.45,
            26
        ];
        $expectedMax = [
            13.4,
            25.99,
            40.3
        ];

        $actualMin = [];
        $actualMax = [];
        $response = $this->getRealResponseData();
        if ($response->hasMainFilters() && $response->getMainFilterCount() > 0) {
            foreach ($response->getMainFilters() as $filter) {
                if (count($filter->getItems()) > 0) {
                    /** @var RangeSliderItem $item */
                    foreach ($filter->getItems() as $item) {
                        if ($item->getParameters()) {
                            $actualMin[] = $item->getParameters()->getMin();
                            $actualMax[] = $item->getParameters()->getMax();
                        }
                    }
                } else {
                    $this->fail('The demo response should have items.');
                }
            }
        } else {
            $this->fail('The demo response should have filters.');
        }

        $this->assertEquals($expectedMin, $actualMin);
        $this->assertEquals($expectedMax, $actualMax);
    }

    public function testResponseWillReturnSubItemsOfItemsAsExpected()
    {
        $expectedSubItemDetails = [
            'name' => 'Beste Bücher',
            'weight' => 0.33799207210541,
            'frequency' => 0,
            'items' => [],
            'selected' => false,
        ];

        $actualSubItemDetails = [];
        $response = $this->getRealResponseData();
        if ($response->hasOtherFilters() && $response->getOtherFilterCount() > 0) {
            foreach ($response->getOtherFilters() as $filter) {
                if (count($filter->getItems()) > 0) {
                    foreach ($filter->getItems() as $item) {
                        if ($item instanceof CategoryItem && $item->getItems()) {
                            foreach ($item->getItems() as $subItem) {
                                $actualSubItemDetails['name'] = $subItem->getName();
                                $actualSubItemDetails['weight'] = $subItem->getWeight();
                                $actualSubItemDetails['frequency'] = $subItem->getFrequency();
                                $actualSubItemDetails['items'] = $subItem->getItems();
                                $actualSubItemDetails['selected'] = $subItem->isSelected();
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

    public function testResponseWillNotBreakIfNoProductsAreFound()
    {
        $response = $this->getRealResponseData('demoResponseWithoutProducts.xml');

        $this->assertCount(0, $response->getProducts());
    }

    public function testResponseWillNotBreakIfNoMainFiltersAreFound()
    {
        $response = $this->getRealResponseData('demoResponseWithoutMainFilters.xml');

        $this->assertSame(0, $response->getMainFilterCount());
        $this->assertEmpty($response->getMainFilters());
        $this->assertFalse($response->hasMainFilters());
    }

    public function testResponseWillNotBreakIfNoOtherFiltersAreFound()
    {
        $response = $this->getRealResponseData('demoResponseWithoutOtherFilters.xml');

        $this->assertSame(0, $response->getOtherFilterCount());
        $this->assertEmpty($response->getOtherFilters());
        $this->assertFalse($response->hasOtherFilters());
    }

    public function testResponseWithMinOrMaxZeroWillNotBeConvertedToNull()
    {
        $response = $this->getRealResponseData('demoResponseWithMinAndMaxZero.xml');

        /** @var RangeSliderFilter $priceFilter */
        $priceFilter = $response->getMainFilters()['price'];
        $this->assertSame(0.0, $priceFilter->getAttributes()->getSelectedRange()->getMin());
        $this->assertSame(0.0, $priceFilter->getAttributes()->getSelectedRange()->getMax());
        $this->assertSame(0.0, $priceFilter->getAttributes()->getTotalRange()->getMin());
        $this->assertSame(0.0, $priceFilter->getAttributes()->getTotalRange()->getMax());
    }
}
