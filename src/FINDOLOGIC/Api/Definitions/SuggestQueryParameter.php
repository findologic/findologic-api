<?php

namespace FINDOLOGIC\Api\Definitions;

class SuggestQueryParameter
{
    const
        AUTOCOMPLETEBLOCKS = 'autocompleteblocks',
        USERGROUPHASH = 'usergrouphash',
        MULTISHOP_ID = 'multishop_id';

    private static $list = [
        self::AUTOCOMPLETEBLOCKS,
        self::USERGROUPHASH,
        self::MULTISHOP_ID,
    ];

    /**
     * Returns an array of all available suggest query parameters.
     * @return array
     */
    public static function getList()
    {
        return self::$list;
    }
}
