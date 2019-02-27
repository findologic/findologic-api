<?php

namespace FINDOLOGIC\Api\Definitions;

class BlockType
{
    const
        SUGGEST_BLOCK = 'suggest',
        LANDINGPAGE_BLOCK = 'landingpage',
        CAT_BLOCK = 'cat',
        VENDOR_BLOCK = 'vendor',
        ORDERNUMBER_BLOCK = 'ordernumber',
        PRODUCT_BLOCK = 'product',
        PROMOTION_BLOCK = 'promotion';

    private static $availableBlockTypes = [
        self::SUGGEST_BLOCK,
        self::LANDINGPAGE_BLOCK,
        self::CAT_BLOCK,
        self::VENDOR_BLOCK,
        self::ORDERNUMBER_BLOCK,
        self::PRODUCT_BLOCK,
        self::PROMOTION_BLOCK
    ];

    /**
     * Returns an array of all available block types.
     * @return array
     */
    public static function getAvailableBlockTypes()
    {
        return self::$availableBlockTypes;
    }
}
