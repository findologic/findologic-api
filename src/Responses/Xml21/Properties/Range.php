<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Range
{
    private ?float $min;
    private ?float $max;

    public function __construct(SimpleXMLElement $response)
    {
        $this->min = ResponseHelper::getFloatProperty($response, 'min', true);
        $this->max = ResponseHelper::getFloatProperty($response, 'max', true);
    }

    public function getMin(): ?float
    {
        return $this->min;
    }

    public function getMax(): ?float
    {
        return $this->max;
    }
}
