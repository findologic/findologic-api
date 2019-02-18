<?php

namespace FINDOLOGIC\Api\Definitions;

class Endpoint
{
    const ALIVETEST = 'alivetest.php';
    const SEARCH = 'index.php';
    const NAVIGATION = 'selector.php';
    const SUGGESTION = 'autocomplete.php';

    private static $availableRequestTypes = [
        self::ALIVETEST,
        self::SEARCH,
        self::NAVIGATION,
        self::SUGGESTION,
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
