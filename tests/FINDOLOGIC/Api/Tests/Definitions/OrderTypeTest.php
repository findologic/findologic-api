<?php

namespace FINDOLOGIC\Api\Tests\Definitions;

use FINDOLOGIC\Api\Definitions\OrderType;
use FINDOLOGIC\Api\Tests\TestBase;

class OrderTypeTest extends TestBase
{
    public function testAllOrderTypesAreAvailable()
    {
        $expectedAvailableOrderTypes = [
            'RELEVANCE' => 'rank',
            'PRICE_ASCENDING' => 'price ASC',
            'PRICE_DESCENDING' => 'price DESC',
            'ALPHABETICAL' => 'label ASC',
            'TOP_SELLERS_FIRST' => 'salesfrequency DESC',
            'NEWEST_FIRST' => 'dateadded DESC'
        ];
        $availableOrderTypes = OrderType::getConstants();

        $this->assertEquals($expectedAvailableOrderTypes, $availableOrderTypes);
    }
}
