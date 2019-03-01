<?php

namespace FINDOLOGIC\Api\Definitions;

class OrderType
{
    const RELEVANCE = 'rank';
    const INEXPENSIVE_PRODUCTS_FIRST = 'price ASC';
    const EXPENSIVE_PRODUCTS_FIRST = 'price DESC';
    const A_Z_SORTING = 'label ASC';
    const TOP_SELLERS_FIRST = 'salesfrequency DESC';
    const NEWEST_PRODUCTS_FIRST = 'dateadded DESC';

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
