<?php

namespace FINDOLOGIC\Api\Definitions;

class Endpoint
{
    const ALIVETEST = 'alivetest.php';
    const SEARCH = 'index.php';
    const NAVIGATION = 'selector.php';
    const SUGGESTION = 'autocomplete.php';

    private static $availableEndpoints = [
        self::ALIVETEST,
        self::SEARCH,
        self::NAVIGATION,
        self::SUGGESTION,
    ];

    /**
     * Returns an array of all available endpoints.
     * @return array
     */
    public static function getAvailableEndpoints()
    {
        return self::$availableEndpoints;
    }
}
