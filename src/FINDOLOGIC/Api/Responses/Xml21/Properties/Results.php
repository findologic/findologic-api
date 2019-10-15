<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Results
{
    /** @var int $count */
    private $count;

    /**
     * Results constructor.
     * @param SimpleXMLElement $result
     */
    public function __construct($result)
    {
        $this->count = ResponseHelper::getIntProperty($result, 'count', true);
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }
}