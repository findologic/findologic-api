<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Definitions\Defaults;
use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Promotion
{
    private string $name;
    private string $url;
    private string $imageUrl;

    /**
     * @param array<string, string> $promotion
     */
    public function __construct(array $promotion)
    {
        $this->name = ResponseHelper::getStringProperty($promotion, 'name') ?? Defaults::EMPTY;
        $this->url = ResponseHelper::getStringProperty($promotion, 'url') ?? Defaults::EMPTY;
        $this->imageUrl = ResponseHelper::getStringProperty($promotion, 'imageUrl') ?? Defaults::EMPTY;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
}
