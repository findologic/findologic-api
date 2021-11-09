<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests\Definitions;

use FINDOLOGIC\Api\Definitions\FilterMode;
use FINDOLOGIC\Api\Tests\TestBase;

class FilterModeTest extends TestBase
{
    public function testAllFilterModesAreAvailable(): void
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
