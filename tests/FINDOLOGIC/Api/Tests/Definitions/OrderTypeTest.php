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
            'NEWEST_FIRST' => 'dateadded DESC',
            'FIELD_PRICE' => 'price',
            'FIELD_LABEL' => 'label',
            'FIELD_SALES_FREQUENCY' => 'salesfrequency',
            'FIELD_DATE_ADDED' => 'dateadded',
            'DIRECTION_ASCENDING' => 'ASC',
            'DIRECTION_DESCENDING' => 'DESC',
            'DEFAULT_SORT' => 'rank',
        ];
        $availableOrderTypes = OrderType::getConstants();

        $this->assertEquals($expectedAvailableOrderTypes, $availableOrderTypes);
    }
}
