<?php

namespace FINDOLOGIC\Api\Responses\Xml20\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

/**
 * @deprecated Use XML 2.1 instead. This class will be removed with version v1.0.0-rc.1.
 */
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
