<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Promotion
{
    private string $name;
    private string $url;
    private string $imageUrl;

    public function __construct(array $promotion)
    {
        $this->name = ResponseHelper::getStringProperty($promotion, 'name');
        $this->url = ResponseHelper::getStringProperty($promotion, 'url');
        $this->imageUrl = ResponseHelper::getStringProperty($promotion, 'imageUrl');
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
