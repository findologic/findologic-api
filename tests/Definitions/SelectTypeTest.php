<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests\Definitions;

use FINDOLOGIC\Api\Definitions\SelectType;
use FINDOLOGIC\Api\Tests\TestBase;

class SelectTypeTest extends TestBase
{
    public function testAllSelectTypesAreAvailable()
    {
        $expectedAvailableSelectTypes = [
            'SINGLE' => 'single',
            'MULTIPLE' => 'multiple',
            'MULTI_SELECT' => 'multiselect'
        ];
        $availableSelectTypes = SelectType::getConstants();

        $this->assertEquals($expectedAvailableSelectTypes, $availableSelectTypes);
    }
}
