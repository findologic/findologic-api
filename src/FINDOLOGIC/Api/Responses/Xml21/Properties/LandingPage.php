<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class LandingPage
{
    /** @var string $link */
    private $link;

    public function __construct(SimpleXMLElement $response)
    {
        $this->link = ResponseHelper::getStringProperty($response, 'link');
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
}
