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
            'ALPHABETICAL_ASCENDING' => 'label ASC',
            'ALPHABETICAL_DESCENDING' => 'label DESC',
            'TOP_SELLERS_FIRST' => 'salesfrequency DESC',
            'TOP_SELLERS_DYNAMIC_FIRST' => 'salesfrequency dynamic DESC',
            'TOP_SELLERS_LAST' => 'salesfrequency ASC',
            'TOP_SELLERS_DYNAMIC_LAST' => 'salesfrequency dynamic ASC',
            'NEWEST_FIRST' => 'dateadded DESC',
            'NEWEST_LAST' => 'dateadded ASC',
            'SHOPSORT_ASCENDING' => 'shopsort ASC',
            'SHOPSORT_DESCENDING' => 'shopsort DESC'
        ];
        $availableOrderTypes = OrderType::getConstants();

        $this->assertEquals($expectedAvailableOrderTypes, $availableOrderTypes);
    }
}
