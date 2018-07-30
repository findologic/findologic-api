<?php

namespace FINDOLOGIC_DEV\Definitions;

class OrderType
{
    const RELEVANCE = 'rank';
    const INEXPENSIVE_FIRST = 'price ASC';
    const EXPENSIVE_FIRST = 'price DESC';
    const A_Z_SORTING = 'label ASC';
    const Z_A_SORTING = 'label DESC';
    const TOP_SELLERS_FIRST = 'salesfrequency DESC';
    const NEWEST_PRODUCTS_FIRST = 'dateadded DESC';

    public static $list = [
        self::RELEVANCE => self::RELEVANCE,
        self::INEXPENSIVE_FIRST => self::INEXPENSIVE_FIRST,
        self::EXPENSIVE_FIRST => self::EXPENSIVE_FIRST,
        self::A_Z_SORTING => self::A_Z_SORTING,
        self::Z_A_SORTING => self::Z_A_SORTING,
        self::TOP_SELLERS_FIRST => self::TOP_SELLERS_FIRST,
        self::NEWEST_PRODUCTS_FIRST => self::NEWEST_PRODUCTS_FIRST,
    ];

    /**
     * Returns an array of all available request types.
     * @return array
     */
    public static function getList()
    {
        return self::$list;
    }
}