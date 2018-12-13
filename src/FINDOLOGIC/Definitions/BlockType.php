<?php

namespace FINDOLOGIC\Definitions;

class BlockType
{
    const SUGGEST_BLOCK = 'suggest';
    const LANDINGPAGE_BLOCK = 'landingpage';
    const CAT_BLOCK = 'cat';
    const VENDOR_BLOCK = 'vendor';
    const PRODUCT_BLOCK = 'product';
    const PROMOTION_BLOCK = 'promotion';

    public static $list = [
        self::SUGGEST_BLOCK,
        self::LANDINGPAGE_BLOCK,
        self::CAT_BLOCK,
        self::VENDOR_BLOCK,
        self::PRODUCT_BLOCK,
        self::PROMOTION_BLOCK
    ];

    /**
     * Returns an array of all available block types.
     * @return array
     */
    public static function getList()
    {
        return self::$list;
    }
}
