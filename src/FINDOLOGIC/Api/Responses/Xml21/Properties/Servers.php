<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Servers
{
    /** @var string $frontend */
    private $frontend;

    /** @var string $backend */
    private $backend;

    public function __construct(SimpleXMLElement $response)
    {
        $this->frontend = ResponseHelper::getStringProperty($response, 'frontend');
        $this->backend = ResponseHelper::getStringProperty($response, 'backend');
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
