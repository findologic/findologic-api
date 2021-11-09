<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Item;

use FINDOLOGIC\Api\Responses\Xml21\Properties\Range;
use SimpleXMLElement;

class RangeSliderItem extends Item
{
    protected bool $selected = false;
    protected ?Range $parameters;

    public function __construct(SimpleXMLElement $item)
    {
        parent::__construct($item);
        if ($item->parameters) {
            $this->parameters = new Range($item->parameters);
        }
    }

    public function getParameters(): ?Range
    {
        return $this->parameters;
    }
}
