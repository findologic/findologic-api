<?php

namespace FINDOLOGIC\Api\ResponseObjects\Xml20\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class LandingPage
{
    /** @var string $link */
    private $link;

    /**
     * LandingPage constructor.
     * @param SimpleXMLElement $response
     */
    public function __construct($response)
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
