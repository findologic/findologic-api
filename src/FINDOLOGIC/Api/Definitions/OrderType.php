<?php

namespace FINDOLOGIC\Api\Definitions;

class OrderType
{
    const
        RELEVANCE = 'rank',
        PRICE_ASCENDING = 'price ASC',
        PRICE_DESCENDING = 'price DESC',
        ALPHABETICAL = 'label ASC',
        TOP_SELLERS_FIRST = 'salesfrequency DESC',
        NEWEST_FIRST = 'dateadded DESC';

    private static $list = [
        self::RELEVANCE,
        self::PRICE_ASCENDING,
        self::PRICE_DESCENDING,
        self::ALPHABETICAL,
        self::TOP_SELLERS_FIRST,
        self::NEWEST_FIRST,
    ];

    /**
     * Returns an array of all available order types.
     * @return array
     */
    public static function getList()
    {
        return self::$list;
    }
}
