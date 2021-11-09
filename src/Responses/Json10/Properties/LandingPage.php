<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class LandingPage
{
    private string $name;
    private string $url;

    /**
     * @param array<string, string> $landingPage
     */
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
