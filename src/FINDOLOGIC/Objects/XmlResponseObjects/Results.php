<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use FINDOLOGIC\Helpers\ResponseHelper;
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
        $this->count = ResponseHelper::getProperty($response, 'count', 'int', true);
    }

    /**
     * @return int|null
     */
    public function getCount()
    {
        return $this->count;
    }
}
