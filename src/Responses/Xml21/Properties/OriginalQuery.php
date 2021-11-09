<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class OriginalQuery
{
    private string $value;
    private bool $allowOverride;

    public function __construct(SimpleXMLElement $response)
    {
        $this->value = (string)$response;
        $this->allowOverride = (bool)ResponseHelper::getBoolProperty(
            $response->attributes(),
            'allow-override'
        );
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getAllowOverride(): bool
    {
        return $this->allowOverride;
    }
}
