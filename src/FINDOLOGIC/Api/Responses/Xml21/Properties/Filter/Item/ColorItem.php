<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Item;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class ColorItem extends Item
{
    /** @var string|null $color */
    protected $color;

    /** @var string|null */
    protected $image;

    public function __construct(SimpleXMLElement $item)
    {
        parent::__construct($item);
        $this->color = ResponseHelper::getStringProperty($item, 'color');
        $this->image = ResponseHelper::getStringProperty($item, 'image');
    }

    /**
     * @return string|null
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return string|null
     */
    public function getImage()
    {
        return $this->image;
    }
}
