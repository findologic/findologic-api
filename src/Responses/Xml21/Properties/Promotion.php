<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Promotion
{
    private string $image;
    private string $link;

    public function __construct(SimpleXMLElement $response)
    {
        $this->image = ResponseHelper::getStringProperty($response, 'image');
        $this->link = ResponseHelper::getStringProperty($response, 'link');
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
