<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class LandingPage
{
    /** @var string */
    private $name;

    /** @var string */
    private $url;

    public function __construct(array $landingPage)
    {
        $this->name = ResponseHelper::getStringProperty($landingPage, 'name');
        $this->url = ResponseHelper::getStringProperty($landingPage, 'url');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
