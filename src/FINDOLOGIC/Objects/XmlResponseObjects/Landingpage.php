<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

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
        $this->link = (string)$response->link;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
}
