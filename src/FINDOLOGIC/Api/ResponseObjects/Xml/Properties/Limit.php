<?php

namespace FINDOLOGIC\Api\ResponseObjects\Xml\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Limit
{
    /** @var int $first */
    private $first;

    /** @var int $count */
    private $count;

    /**
     * Limit constructor.
     * @param SimpleXMLElement $response
     */
    public function __construct($response)
    {
        $this->first = ResponseHelper::getIntProperty($response, 'first', true);
        $this->count = ResponseHelper::getIntProperty($response, 'count', true);
    }

    /**
     * @return int
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }
}
