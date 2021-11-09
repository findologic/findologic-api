<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Results
{
    private int $count;

    public function __construct(SimpleXMLElement $response)
    {
        $this->count = ResponseHelper::getIntProperty($response, 'count', true);
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
