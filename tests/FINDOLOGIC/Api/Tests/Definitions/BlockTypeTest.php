<?php

namespace FINDOLOGIC\Api\Tests\Definitions;

use FINDOLOGIC\Api\Definitions\BlockType;
use FINDOLOGIC\Api\Tests\TestBase;

class BlockTypeTest extends TestBase
{
    public function testAllBlockTypesAreAvailable()
    {
        $expectedAvailableBlockTypes = [
            'SUGGEST_BLOCK' => 'suggest',
            'LANDINGPAGE_BLOCK' => 'landingpage',
            'CAT_BLOCK' => 'cat',
            'VENDOR_BLOCK' => 'vendor',
            'ORDERNUMBER_BLOCK' => 'ordernumber',
            'PRODUCT_BLOCK' => 'product',
            'PROMOTION_BLOCK' => 'promotion'
        ];
        $availableBlockTypes = BlockType::getConstants();

        $this->assertEquals($expectedAvailableBlockTypes, $availableBlockTypes);
    }
}
