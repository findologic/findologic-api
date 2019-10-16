<?php

namespace FINDOLOGIC\Api\Responses\Xml20\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

/**
 * @deprecated Use XML 2.1 instead. This class will be removed with version v1.0.0-rc.1.
 */
class OriginalQuery
{
    /** @var string $value */
    private $value;

    /** @var bool|null $allowOverride */
    private $allowOverride;

    /**
     * OriginalQuery constructor.
     * @param SimpleXMLElement $response
     */
    public function __construct($response)
    {
        $this->value = (string)$response;

        $this->allowOverride = ResponseHelper::getBoolProperty($response->attributes(), 'allow-override');
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return bool|null
     */
    public function getAllowOverride()
    {
        return $this->allowOverride;
    }
}
