<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use SimpleXMLElement;

class Servers
{
    /** @var string $frontend */
    private $frontend;

    /** @var string $backend */
    private $backend;

    /**
     * Servers constructor.
     * @param SimpleXMLElement $response
     */
    public function __construct($response)
    {
        $this->frontend = (string)$response->frontend;
        $this->backend = (string)$response->backend;
    }

    /**
     * @return string
     */
    public function getFrontend()
    {
        return $this->frontend;
    }

    /**
     * @return string
     */
    public function getBackend()
    {
        return $this->backend;
    }
}
