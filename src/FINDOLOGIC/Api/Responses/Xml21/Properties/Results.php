<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Results
{
    /** @var int $count */
    private $count;

    public function __construct(SimpleXMLElement $response)
    {
        $this->count = ResponseHelper::getIntProperty($response, 'count', true);
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }
}
