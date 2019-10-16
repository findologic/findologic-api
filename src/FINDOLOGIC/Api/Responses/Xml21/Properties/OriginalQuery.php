<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class OriginalQuery
{
    /** @var string $value */
    private $value;

    /** @var bool|null $allowOverride */
    private $allowOverride;

    public function __construct(SimpleXMLElement $response)
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
