<?php

namespace FINDOLOGIC\Api\Definitions;

class OrderType extends Definition
{
    const
        RELEVANCE = 'rank',
        PRICE_ASCENDING = 'price ASC',
        PRICE_DESCENDING = 'price DESC',
        ALPHABETICAL = 'label ASC',
        TOP_SELLERS_FIRST = 'salesfrequency DESC',
        NEWEST_FIRST = 'dateadded DESC';
}
