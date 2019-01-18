<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use FINDOLOGIC\Helpers\ResponseHelper;
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
        $this->link = ResponseHelper::getProperty($response, 'link', 'string');
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
}
