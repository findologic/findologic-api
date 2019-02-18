<?php

namespace FINDOLOGIC\Api\Definitions;

class QueryParameter
{
    const SHOPKEY = 'shopkey';
    const SHOP_URL = 'shopurl';
    const USER_IP = 'userip';
    const REFERER = 'referer';
    const REVISION = 'revision';
    const QUERY = 'query';
    const ATTRIB = 'attrib';
    const ORDER = 'order';
    const PROPERTIES = 'properties';
    const PUSH_ATTRIB = 'pushAttrib';
    const COUNT = 'count';
    const FIRST = 'first';
    const IDENTIFIER = 'identifier';
    const GROUP = 'group';
    const FORCE_ORIGINAL_QUERY = 'forceOriginalQuery';

    private static $list = [
        self::SHOP_URL,
        self::USER_IP,
        self::REFERER,
        self::REVISION,
        self::QUERY,
        self::ATTRIB,
        self::ORDER,
        self::PROPERTIES,
        self::PUSH_ATTRIB,
        self::COUNT,
        self::FIRST,
        self::IDENTIFIER,
        self::GROUP,
        self::FORCE_ORIGINAL_QUERY,
    ];

    /**
     * Returns an array of all available query parameters.
     * @return array
     */
    public static function getList()
    {
        return self::$list;
    }
}
