<?php

namespace FINDOLOGIC\Api\Definitions;

class OrderType extends Definition
{
    const
        RELEVANCE = 'rank',
        PRICE_ASCENDING = 'price ASC',
        PRICE_DESCENDING = 'price DESC',
        ALPHABETICAL = 'label ASC',
        REVERSE_ALPHABETICAL = 'label DESC',
        TOP_SELLERS_FIRST = 'salesfrequency DESC',
        TOP_SELLERS_DYNAMIC_FIRST = 'salesfrequency dynamic DESC',
        TOP_SELLERS_LAST = 'salesfrequency ASC',
        TOP_SELLERS_DYNAMIC_LAST = 'salesfrequency dynamic ASC',
        NEWEST_FIRST = 'dateadded DESC',
        NEWEST_LAST = 'dateadded ASC';
}
