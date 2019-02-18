<?php

namespace FINDOLOGIC\Api\Objects\XmlResponseObjects;

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
        $this->first = (int)$response->first;
        $this->count = (int)$response->count;
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
