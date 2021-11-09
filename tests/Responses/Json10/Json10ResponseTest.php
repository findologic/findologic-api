<?php

namespace FINDOLOGIC\Api\Tests\Responses\Json10;

use FINDOLOGIC\Api\Responses\Json10\Json10Response;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\ColorFilter;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\ImageFilter;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\LabelFilter;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Range;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\RangeSliderFilter;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\SelectFilter;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Values\ColorFilterValue;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Values\DefaultFilterValue;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Values\ImageFilterValue;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Values\RangeSliderValue;
use FINDOLOGIC\Api\Responses\Json10\Properties\Item;
use FINDOLOGIC\Api\Responses\Json10\Properties\LandingPage;
use FINDOLOGIC\Api\Responses\Json10\Properties\Promotion;
use PHPUnit\Framework\TestCase;

class Json10ResponseTest extends TestCase
{
    /**
     * Will use a real response that could come from a request. It returns the Object.
     *
     * @param string $filename
     *
     * @return Json10Response
     */
    public function getRealResponseData($filename = 'demoResponse.json')
    {
        // Get contents from a real response locally.
        $realResponseData = file_get_contents(__DIR__ . '/../../Mockdata/Json10/' . $filename);

        return new Json10Response($realResponseData);
    }

    public function testRequestWillBeReturnedAsExpected()
    {
        $response = $this->getRealResponseData();
        $request = $response->getRequest();

        $this->assertNull($request->getQuery());
        $this->assertSame(0, $request->getFirst());
        $this->assertSame(24, $request->getCount());
        $this->assertSame('ABCD1234ABCD1234ABCD1234ABCD1234', $request->getServiceId());
        $this->assertNull($request->getUsergroup());

        $order = $request->getOrder();

        $this->assertSame('salesfrequency', $order->getField());
        $this->assertTrue($order->isRelevanceBased());
        $this->assertSame('DESC', $order->getDirection());
        $this->assertSame('salesfrequency dynamic DESC', $order->__toString());
    }

    public function testMetadataWillBeReturnedAsExpected()
    {
        $response = $this->getRealResponseData();
        $metadata = $response->getResult()->getMetadata();

        $this->assertNull($metadata->getLandingPage());
        $this->assertNull($metadata->getPromotion());
        $this->assertNull($metadata->getSearchConcept());
        $this->assertSame(168, $metadata->getTotalResults());
        $this->assertSame('€', $metadata->getCurrencySymbol());
    }

    public function testItemsWillBeReturnedAsExpected()
    {
        $response = $this->getRealResponseData();
        $items = $response->getResult()->getItems();

        $this->assertCount(24, $items);
        foreach ($items as $item) {
            $this->assertIsFloat($item->getPrice());
        }

        $firstItem = $items[0];
        $this->assertSame('631de16d8e74471dbaa0b69a16a8bc4c', $firstItem->getId());
        $this->assertSame(2.19722, $firstItem->getScore());
        $this->assertSame(
            'http://blubbergurken.de/detail/631de16d8e74471dbaa0b69a16a8bc4c',
            $firstItem->getUrl()
        );
        $this->assertSame('Click Frame 21 cm Wood', $firstItem->getName());
        $this->assertSame('Click Frame 21 cm Wood', $firstItem->getHighlightedName());
        $this->assertSame(['16490.6.1'], $firstItem->getOrdernumbers());
        $this->assertNull($firstItem->getMatchingOrdernumber());
        $this->assertSame(5.95, $firstItem->getPrice());
        $this->assertNull($firstItem->getSummary());
        $this->assertSame('nice product placement', $firstItem->getProductPlacement());
        $this->assertSame(['Awesome PushRule!'], $firstItem->getPushRules());
        $this->assertSame(
            'http://blubbergurken.de/media/7b/ea/45/1585143022/Click-Frame-21-cm-Wood-16490_6_1_001.jpg',
            $firstItem->getImageUrl()
        );
    }

    public function testVariantIsReturnedAsExpected()
    {
        $response = $this->getRealResponseData();
        $variant = $response->getResult()->getVariant();

        $this->assertSame('sdym', $variant->getName());
        $this->assertNull($variant->getCorrectedQuery());
        $this->assertNull($variant->getDidYouMeanQuery());
        $this->assertNull($variant->getImprovedQuery());
    }

    public function testMainFiltersAreReturnedAsExpected()
    {
        $response = $this->getRealResponseData();
        $mainFilters = $response->getResult()->getMainFilters();

        $this->assertCount(3, $mainFilters);

        $this->assertInstanceOf(SelectFilter::class, $mainFilters[0]);
        /** @var SelectFilter $firstFilter */
        $firstFilter = $mainFilters[0];
        $this->assertSame(6, $firstFilter->getPinnedFilterValueCount());
        $this->assertSame('cat', $firstFilter->getName());
        $this->assertSame('Kategorie', $firstFilter->getDisplayName());
        $this->assertSame('single', $firstFilter->getSelectMode());
        $this->assertNull($firstFilter->getCssClass());
        $this->assertNull($firstFilter->getNoAvailableFiltersText());
        $this->assertNull($firstFilter->getCombinationOperation());
        $this->assertCount(28, $mainFilters[0]->getValues());
        $firstFilterValue = $mainFilters[0]->getValues()[0];
        $this->assertInstanceOf(DefaultFilterValue::class, $firstFilterValue);
        $this->assertSame('Alle Kategorien', $firstFilterValue->getName());
        $this->assertFalse($firstFilterValue->isSelected());
        $this->assertSame(0.0, $firstFilterValue->getWeight());
        $this->assertSame(168, $firstFilterValue->getFrequency());

        $this->assertInstanceOf(LabelFilter::class, $mainFilters[1]);
        /** @var LabelFilter $secondFilter */
        $secondFilter = $mainFilters[1];
        $this->assertSame('vendor', $secondFilter->getName());
        $this->assertSame('Hersteller', $secondFilter->getDisplayName());
        $this->assertSame('multiple', $secondFilter->getSelectMode());
        $this->assertNull($secondFilter->getCssClass());
        $this->assertNull($secondFilter->getNoAvailableFiltersText());
        $this->assertSame('and', $secondFilter->getCombinationOperation());
        $this->assertCount(4, $secondFilter->getValues());
        $secondFilterValue = $mainFilters[1]->getValues()[0];
        $this->assertInstanceOf(DefaultFilterValue::class, $secondFilterValue);
        $this->assertSame('Casablanca', $secondFilterValue->getName());
        $this->assertFalse($secondFilterValue->isSelected());
        $this->assertSame(0.0119, $secondFilterValue->getWeight());
        $this->assertNull($secondFilterValue->getFrequency());

        $this->assertInstanceOf(RangeSliderFilter::class, $mainFilters[2]);
        /** @var RangeSliderFilter $thirdFilter */
        $thirdFilter = $mainFilters[2];
        $this->assertSame(0.1, $thirdFilter->getStepSize());
        $this->assertSame('€', $thirdFilter->getUnit());
        $this->assertSame(5.95, $thirdFilter->getTotalRange()->getMin());
        $this->assertSame(999.0, $thirdFilter->getTotalRange()->getMax());
        $this->assertEquals(new Range([
            'min' => 5.95,
            'max' => 999.0
        ]), $thirdFilter->getTotalRange());
        $this->assertEquals(new Range([
            'min' => 5.95,
            'max' => 999.0
        ]), $thirdFilter->getSelectedRange());
        $this->assertSame('price', $thirdFilter->getName());
        $this->assertSame('Preis', $thirdFilter->getDisplayName());
        $this->assertSame('single', $thirdFilter->getSelectMode());
        $this->assertNull($thirdFilter->getCssClass());
        $this->assertNull($thirdFilter->getNoAvailableFiltersText());
        $this->assertNull($thirdFilter->getCombinationOperation());
        $this->assertCount(4, $thirdFilter->getValues());
        /** @var RangeSliderValue $thirdFilterValue */
        $thirdFilterValue = $mainFilters[2]->getValues()[0];
        $this->assertInstanceOf(RangeSliderValue::class, $thirdFilterValue);
        $this->assertFalse($thirdFilterValue->isSelected());
        $this->assertSame(5.95, $thirdFilterValue->getMin());
        $this->assertSame(11.95, $thirdFilterValue->getMax());
        $this->assertSame('5.95 - 11.95', $thirdFilterValue->getName());
        $this->assertSame(0.6667, $thirdFilterValue->getWeight());
        $this->assertNull($thirdFilterValue->getFrequency());
    }

    public function testOtherFiltersAreReturnedAsExpected()
    {
        $response = $this->getRealResponseData();
        $otherFilters = $response->getResult()->getOtherFilters();

        $this->assertCount(2, $otherFilters);
        $otherFilter = $otherFilters[0];
        $this->assertInstanceOf(LabelFilter::class, $otherFilter);
        $this->assertSame('shipping_free', $otherFilter->getName());
        $this->assertSame('Versandkostenfrei', $otherFilter->getDisplayName());
        $this->assertSame('multiple', $otherFilter->getSelectMode());
        $this->assertNull($otherFilter->getCssClass());
        $this->assertNull($otherFilter->getNoAvailableFiltersText());
        $this->assertSame('and', $otherFilter->getCombinationOperation());

        $this->assertCount(2, $otherFilter->getValues());
        $otherFilterValue = $otherFilter->getValues()[0];
        $this->assertInstanceOf(DefaultFilterValue::class, $otherFilterValue);
        $this->assertSame('0', $otherFilterValue->getName());
        $this->assertFalse($otherFilterValue->isSelected());
        $this->assertSame(0.0119, $otherFilterValue->getWeight());
        $this->assertSame(167, $otherFilterValue->getFrequency());
    }

    public function testLandingPageIsReturnedAsExpected()
    {
        $response = $this->getRealResponseData('demoResponseWithLandingPage.json');
        $landingPage = $response->getResult()->getMetadata()->getLandingPage();

        $this->assertInstanceOf(LandingPage::class, $landingPage);
        $this->assertSame($landingPage->getName(), 'GTC');
        $this->assertSame($landingPage->getUrl(), 'https://blubbergurken.io/gtc');
    }

    public function testPromotionIsReturnedAsExpected()
    {
        $response = $this->getRealResponseData('demoResponseWithPromotion.json');
        $promotion = $response->getResult()->getMetadata()->getPromotion();

        $this->assertInstanceOf(Promotion::class, $promotion);
        $this->assertSame('Promotion', $promotion->getName());
        $this->assertSame('https://blubbergurken.io/promotion', $promotion->getUrl());
        $this->assertSame('https://blubbergurken.io/assets/images/promotion.png', $promotion->getImageUrl());
    }

    public function testItemPropertiesAreReturnedAsExpected()
    {
        $response = $this->getRealResponseData('demoResponseWithItemProperties.json');
        $items = $response->getResult()->getItems();

        $item = $items[0];
        $this->assertInstanceOf(Item::class, $item);

        $this->assertCount(1, $item->getProperties());
        $this->assertArrayHasKey('ordernumber', $item->getProperties());
        $this->assertSame('YEEEETTTTEERRRR-123', $item->getProperties()['ordernumber']);
        $this->assertSame($item->getProperties()['ordernumber'], $item->getProperty('ordernumber'));
    }

    public function testItemPropertyWillReturnNullIfPropertyDoesNotExist()
    {
        $response = $this->getRealResponseData();
        $items = $response->getResult()->getItems();

        $item = $items[0];
        $this->assertInstanceOf(Item::class, $item);

        $this->assertEmpty($item->getProperties());
        $this->assertNull($item->getProperty('ordernumber'));

        $expectedDefault = 'nice default!';
        $this->assertEquals($expectedDefault, $item->getProperty('ordernumber', $expectedDefault));
    }

    public function testItemAttributesAreReturnedAsExpected()
    {
        $response = $this->getRealResponseData('demoResponseWithItemAttributes.json');
        $items = $response->getResult()->getItems();

        $item = $items[0];
        $this->assertInstanceOf(Item::class, $item);

        $this->assertCount(1, $item->getAttributes());
        $this->assertArrayHasKey('vendor', $item->getAttributes());
        $this->assertSame(['Blubbergurken inc.'], $item->getAttributes()['vendor']);
        $this->assertSame($item->getAttributes()['vendor'], $item->getAttribute('vendor'));
    }

    public function testItemAttributeWillReturnNullIfAttributeDoesNotExist()
    {
        $response = $this->getRealResponseData();
        $items = $response->getResult()->getItems();

        $item = $items[0];
        $this->assertInstanceOf(Item::class, $item);

        $this->assertEmpty($item->getAttributes());
        $this->assertNull($item->getAttribute('vendor'));

        $expectedDefault = 'nice default!';
        $this->assertEquals($expectedDefault, $item->getAttribute('vendor', $expectedDefault));
    }

    public function testColorFilterIsReturnedAsExpected()
    {
        $response = $this->getRealResponseData('demoResponseWithColorFilter.json');
        $filters = $response->getResult()->getOtherFilters();

        /** @var ColorFilter $colorFilter */
        $colorFilter = $filters[0];
        $this->assertInstanceOf(ColorFilter::class, $colorFilter);
        $this->assertSame('color', $colorFilter->getName());
        $this->assertSame('Farbe', $colorFilter->getDisplayName());
        $this->assertSame('multiple', $colorFilter->getSelectMode());
        $this->assertSame('NO COLORS', $colorFilter->getNoAvailableFiltersText());
        $this->assertSame('or', $colorFilter->getCombinationOperation());

        $this->assertCount(108, $colorFilter->getValues());
        /** @var ColorFilterValue $colorFilterValue */
        $colorFilterValue = $colorFilter->getValues()[0];
        $this->assertSame('#3289c7', $colorFilterValue->getColor());
        $this->assertFalse($colorFilterValue->isSelected());
        $this->assertSame(
            'https://blubbergurken.io/assets/images/color/antiquewhite.png',
            $colorFilterValue->getImage()
        );
        $this->assertSame('antiquewhite', $colorFilterValue->getName());
        $this->assertSame(0.0667, $colorFilterValue->getWeight());
        $this->assertNull($colorFilterValue->getFrequency());
    }

    public function testVendorImageFilterIsReturnedAsExpected()
    {
        $response = $this->getRealResponseData('demoResponseWithVendorImageFilter.json');
        $filters = $response->getResult()->getMainFilters();

        $imageFilter = $filters[1];
        $this->assertInstanceOf(ImageFilter::class, $imageFilter);
        $this->assertSame('vendor', $imageFilter->getName());
        $this->assertSame('Hersteller', $imageFilter->getDisplayName());
        $this->assertSame('multiple', $imageFilter->getSelectMode());
        $this->assertSame('and', $imageFilter->getCombinationOperation());

        $this->assertCount(38, $imageFilter->getValues());
        /** @var ImageFilterValue $imageFilterValue */
        $imageFilterValue = $imageFilter->getValues()[0];
        $this->assertInstanceOf(ImageFilterValue::class, $imageFilterValue);
        $this->assertSame(
            'https://blubbergurken.io/assets/images/vendor/anderson_gusikowski_and_barton.png',
            $imageFilterValue->getImage()
        );
        $this->assertSame('Anderson, Gusikowski and Barton', $imageFilterValue->getName());
        $this->assertSame(0.0333, $imageFilterValue->getWeight());
        $this->assertNull($imageFilterValue->getFrequency());
        $this->assertFalse($imageFilterValue->isSelected());
    }
}
