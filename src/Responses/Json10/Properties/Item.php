<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

/**
 * Holds data of an item/product.
 */
class Item extends BaseItem
{
    /** @var string */
    private $highlightedName;

    /** @var string|null */
    private $productPlacement;

    /** @var string[] */
    private $pushRules = [];

    /** @var ItemVariant[] */
    private $variants = [];

    public function __construct(array $item)
    {
        parent::__construct($item);

        $this->highlightedName = ResponseHelper::getStringProperty($item, 'highlightedName');
        $this->productPlacement = ResponseHelper::getStringProperty($item, 'productPlacement');

        if (isset($item['pushRules'])) {
            $this->pushRules = $item['pushRules'];
        }

        if (isset($item['variants'])) {
            $this->variants = array_map(
                function (array $variant) {
                    return new ItemVariant($variant);
                },
                $item['variants']
            );
        }
    }

    /**
     * @return string
     */
    public function getHighlightedName()
    {
        return $this->highlightedName;
    }

    /**
     * @return string|null
     */
    public function getProductPlacement()
    {
        return $this->productPlacement;
    }

    /**
     * @return string[]
     */
    public function getPushRules()
    {
        return $this->pushRules;
    }

    /**
     * @return ItemVariant[]
     */
    public function getVariants()
    {
        return $this->variants;
    }
}
