<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Definitions\Defaults;
use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Servers
{
    private string $frontend;
    private string $backend;

    public function __construct(SimpleXMLElement $response)
    {
        $this->frontend = ResponseHelper::getStringProperty($response, 'frontend') ?? Defaults::EMPTY;
        $this->backend = ResponseHelper::getStringProperty($response, 'backend') ?? Defaults::EMPTY;
    }

    public function getFrontend(): string
    {
        return $this->frontend;
    }

    public function getBackend(): string
    {
        return $this->backend;
    }
}
