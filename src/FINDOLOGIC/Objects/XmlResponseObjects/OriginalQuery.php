<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use Exception;
use FINDOLOGIC\Helpers\ResponseHelper;
use SimpleXMLElement;

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

        $this->allowOverride = ResponseHelper::getProperty($response->attributes(), 'allow-override', 'bool');
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
