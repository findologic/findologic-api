<?php

namespace FINDOLOGIC\Api\Objects\XmlResponseObjects;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Landingpage
{
    /** @var string $link */
    private $link;

    /**
     * Landingpage constructor.
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
