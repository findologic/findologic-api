<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Item;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class ColorItem extends Item
{
    protected ?string $color;
    protected ?string $image;

    public function __construct(SimpleXMLElement $item)
    {
        parent::__construct($item);
        $this->color = ResponseHelper::getStringProperty($item, 'color');
        $this->image = trim(ResponseHelper::getStringProperty($item, 'image'));
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}
