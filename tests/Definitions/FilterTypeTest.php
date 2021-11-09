<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests\Definitions;

use FINDOLOGIC\Api\Definitions\FilterType;
use FINDOLOGIC\Api\Tests\TestBase;

class FilterTypeTest extends TestBase
{
    public function testAllFilterTypesAreAvailable()
    {
        $expectedAvailableFilterTypes = [
            'SELECT' => 'select',
            'LABEL' => 'label',
            'RANGE_SLIDER' => 'range-slider',
            'COLOR' => 'color',
            'COLOR_ALTERNATIVE' => 'color-picker',
            'VENDOR_IMAGE' => 'image',
            'VENDOR_IMAGE_ALTERNATIVE' => 'image-filter'
        ];

        $availableFilterTypes = FilterType::getConstants();

        $this->assertEquals($expectedAvailableFilterTypes, $availableFilterTypes);
    }
}
