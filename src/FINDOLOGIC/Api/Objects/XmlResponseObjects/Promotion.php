<?php

namespace FINDOLOGIC\Api\Objects\XmlResponseObjects;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
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
        $this->image = ResponseHelper::getStringProperty($response, 'image');
        $this->link = ResponseHelper::getStringProperty($response, 'link');
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
