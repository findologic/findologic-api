<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use FINDOLOGIC\Helpers\ResponseHelper;
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
        $this->first = ResponseHelper::getProperty($response, 'first', 'int', true);
        $this->count = ResponseHelper::getProperty($response, 'count', 'int', true);
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
