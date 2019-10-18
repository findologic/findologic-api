<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Range
{
    /** @var float|null $min */
    private $min;

    /** @var float|null $min */
    private $max;

    public function __construct(SimpleXMLElement $response)
    {
        $this->min = ResponseHelper::getFloatProperty($response, 'min');
        $this->max = ResponseHelper::getFloatProperty($response, 'max');
    }

    /**
     * @return float|null
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @return float|null
     */
    public function getMax()
    {
        return $this->max;
    }
}
