<?php

namespace FINDOLOGIC\Api\Tests\Definitions;

use FINDOLOGIC\Api\Definitions\BlockType;
use FINDOLOGIC\Api\Tests\TestBase;

class BlockTypeTest extends TestBase
{
    public function testAllBlockTypesAreAvailable()
    {
        $expectedAvailableBlockTypes = [
            'suggest',
            'landingpage',
            'cat',
            'vendor',
            'ordernumber',
            'product',
            'promotion'
        ];
        $availableBlockTypes = BlockType::getAvailableBlockTypes();

        $this->assertEquals($expectedAvailableBlockTypes, $availableBlockTypes);
    }
}
