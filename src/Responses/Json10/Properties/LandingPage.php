<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Definitions\Defaults;
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
        $this->name = ResponseHelper::getStringProperty($landingPage, 'name') ?? Defaults::EMPTY;
        $this->url = ResponseHelper::getStringProperty($landingPage, 'url') ?? Defaults::EMPTY;
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
