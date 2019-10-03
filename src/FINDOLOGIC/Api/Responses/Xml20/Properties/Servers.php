<?php

namespace FINDOLOGIC\Api\Responses\Xml20\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

/**
 * @deprecated Use XML 2.1 instead. This class will be removed with version v1.0.0-rc.1.
 */
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
