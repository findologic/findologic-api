<?php

namespace FINDOLOGIC\Api\Tests\Definitions;

use FINDOLOGIC\Api\Definitions\FilterMode;
use FINDOLOGIC\Api\Tests\TestBase;

class FilterModeTest extends TestBase
{
    public function testAllFilterModesAreAvailable()
    {
        $expectedAvailableFilterModes = [
            'SINGLE' => 'single',
            'MULTIPLE' => 'multiple',
            'MULTISELECT' => 'multiselect'
        ];
        $availableFilterModes = FilterMode::getConstants();

        $this->assertEquals($expectedAvailableFilterModes, $availableFilterModes);
    }
}
