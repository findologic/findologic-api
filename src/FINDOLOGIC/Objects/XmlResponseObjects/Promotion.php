<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use FINDOLOGIC\Helpers\ResponseHelper;
use SimpleXMLElement;

class Promotion
{
    /** @var string $image */
    private $image;

    /** @var string $link */
    private $link;

    /**
     * Promotion constructor.
     * @param SimpleXMLElement $response
     */
    public function __construct($response)
    {
        $this->image = ResponseHelper::getProperty($response, 'image', 'string');
        $this->link = ResponseHelper::getProperty($response, 'link', 'string');
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
}
