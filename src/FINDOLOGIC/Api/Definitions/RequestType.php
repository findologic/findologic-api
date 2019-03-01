<?php

namespace FINDOLOGIC\Api\Definitions;

class RequestType
{
    const ALIVETEST_REQUEST = 'alivetest.php';
    const SEARCH_REQUEST = 'index.php';
    const NAVIGATION_REQUEST = 'selector.php';
    const SUGGESTION_REQUEST = 'autocomplete.php';

    private static $availableRequestTypes = [
        self::ALIVETEST_REQUEST,
        self::SEARCH_REQUEST,
        self::NAVIGATION_REQUEST,
        self::SUGGESTION_REQUEST,
    ];

    /**
     * Returns an array of all available request types.
     * @return array
     */
    public static function getAvailableRequestTypes()
    {
        return self::$availableRequestTypes;
    }
}
