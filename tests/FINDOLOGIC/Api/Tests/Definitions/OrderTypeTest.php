<?php

namespace FINDOLOGIC\Api\Tests\Definitions;

use FINDOLOGIC\Api\Definitions\OrderType;
use FINDOLOGIC\Api\Tests\TestBase;

class OrderTypeTest extends TestBase
{
    public function testAllOrderTypesAreAvailable()
    {
        $expectedAvailableOrderTypes = [
            'rank',
            'price ASC',
            'price DESC',
            'label ASC',
            'salesfrequency DESC',
            'dateadded DESC',
        ];
        $availableOrderTypes = OrderType::getList();

        $this->assertEquals($expectedAvailableOrderTypes, $availableOrderTypes);
    }
}
