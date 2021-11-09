<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class LandingPage
{
    private string $name;
    private string $url;

    public function __construct(array $landingPage)
    {
        $this->name = ResponseHelper::getStringProperty($landingPage, 'name');
        $this->url = ResponseHelper::getStringProperty($landingPage, 'url');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
