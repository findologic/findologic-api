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
            'REVERSE_ALPHABETICAL' => 'label DESC',
            'TOP_SELLERS_FIRST' => 'salesfrequency dynamic DESC',
            'TOP_SELLERS_LAST' => 'salesfrequency dynamic ASC',
            'NEWEST_FIRST' => 'dateadded DESC',
            'NEWEST_LAST' => 'dateadded ASC'
        ];
        $availableOrderTypes = OrderType::getConstants();

        $this->assertEquals($expectedAvailableOrderTypes, $availableOrderTypes);
    }
}
