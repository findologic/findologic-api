<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Item;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class ImageItem extends Item
{
    /** @var string|null $image */
    protected $image;

    public function __construct(SimpleXMLElement $item)
    {
        parent::__construct($item);
        $this->image = ResponseHelper::getStringProperty($item, 'image');
    }

    /**
     * @return string|null
     */
    public function getImage()
    {
        return $this->image;
    }
}
