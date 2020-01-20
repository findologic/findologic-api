<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Item;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class ColorItem extends Item
{
    public function __construct(SimpleXMLElement $item)
    {
        parent::__construct($item);
        $this->color = ResponseHelper::getStringProperty($item, 'color');
        $this->image = ResponseHelper::getStringProperty($item, 'image');
    }
}
