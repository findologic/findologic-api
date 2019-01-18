<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use FINDOLOGIC\Helpers\ResponseHelper;
use SimpleXMLElement;

class Servers
{
    /** @var string|null $frontend */
    private $frontend;

    /** @var string|null $backend */
    private $backend;

    /**
     * Servers constructor.
     * @param SimpleXMLElement $response
     */
    public function __construct($response)
    {
        $this->frontend = ResponseHelper::getProperty($response, 'frontend', 'string');
        $this->backend = ResponseHelper::getProperty($response, 'backend', 'string');
    }

    /**
     * @return string|null
     */
    public function getFrontend()
    {
        return $this->frontend;
    }

    /**
     * @return string|null
     */
    public function getBackend()
    {
        return $this->backend;
    }
}
