<?php

namespace FINDOLOGIC\Api\Definitions;

class OrderType
{
    const
        RELEVANCE = 'rank',
        INEXPENSIVE_PRODUCTS_FIRST = 'price ASC',
        EXPENSIVE_PRODUCTS_FIRST = 'price DESC',
        A_Z_SORTING = 'label ASC',
        TOP_SELLERS_FIRST = 'salesfrequency DESC',
        NEWEST_PRODUCTS_FIRST = 'dateadded DESC';

    private static $list = [
        self::RELEVANCE,
        self::INEXPENSIVE_PRODUCTS_FIRST,
        self::EXPENSIVE_PRODUCTS_FIRST,
        self::A_Z_SORTING,
        self::TOP_SELLERS_FIRST,
        self::NEWEST_PRODUCTS_FIRST,
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
