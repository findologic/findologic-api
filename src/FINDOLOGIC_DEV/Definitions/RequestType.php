<?php

namespace FINDOLOGIC_DEV\Definitions;

class RequestType
{
    const ALIVETEST_REQUEST = 'alivetest.php';
    const SEARCH_REQUEST = 'index.php';
    const NAVIGATION_REQUEST = 'selector.php';
    const SUGGESTION_REQUEST = 'autocomplete.php';

    public static $list = [
        self::ALIVETEST_REQUEST => self::ALIVETEST_REQUEST,
        self::SEARCH_REQUEST => self::SEARCH_REQUEST,
        self::NAVIGATION_REQUEST => self::NAVIGATION_REQUEST,
        self::SUGGESTION_REQUEST => self::SUGGESTION_REQUEST,
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
