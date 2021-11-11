<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Definitions\Defaults;
use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Promotion
{
    private string $image;
    private string $link;

    public function __construct(SimpleXMLElement $response)
    {
        $this->image = ResponseHelper::getStringProperty($response, 'image') ?? Defaults::EMPTY;
        $this->link = ResponseHelper::getStringProperty($response, 'link') ?? Defaults::EMPTY;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getLink(): string
    {
        return $this->link;
    }
}
