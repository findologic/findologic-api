<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use Exception;
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

        try {
            $this->allowOverride = (bool)$response->attributes()['allow-override'];
        } catch (Exception $e) {
            $this->allowOverride = null;
        }
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
