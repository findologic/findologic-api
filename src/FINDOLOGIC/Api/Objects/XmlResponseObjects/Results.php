<?php

namespace FINDOLOGIC\Api\Objects\XmlResponseObjects;

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
        $this->count = (int)$result->count;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }
}
