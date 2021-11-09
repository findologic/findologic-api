<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Item;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class VendorImageItem extends Item
{
    protected ?string $image;

    public function __construct(SimpleXMLElement $item)
    {
        parent::__construct($item);

        if ($image = ResponseHelper::getStringProperty($item, 'image')) {
            $this->image = trim($image);
        }
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}
