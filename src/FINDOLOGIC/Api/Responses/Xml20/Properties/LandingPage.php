<?php

namespace FINDOLOGIC\Api\Responses\Xml20\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

/**
 * @deprecated Use XML 2.1 instead. This class will be removed with version v1.0.0-rc.1.
 */
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
