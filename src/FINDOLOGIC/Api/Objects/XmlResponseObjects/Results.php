<?php

namespace FINDOLOGIC\Api\Objects\XmlResponseObjects;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Results
{
    /** @var int|null $count */
    private $count;

    /**
     * Results constructor.
     * @param SimpleXMLElement $response
     */
    public function __construct($response)
    {
        $this->count = ResponseHelper::getIntProperty($response, 'count', true);
    }

    /**
     * @return int|null
     */
    public function getCount()
    {
        return $this->count;
    }
}
