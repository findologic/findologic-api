<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Limit
{
    /** @var int $first */
    private $first;

    /** @var int $count */
    private $count;

    public function __construct(SimpleXMLElement $response)
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
