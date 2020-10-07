<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Promotion
{
    /** @var string */
    private $name;

    /** @var string */
    private $url;

    /** @var string */
    private $imageUrl;

    public function __construct(array $promotion)
    {
        $this->name = ResponseHelper::getStringProperty($promotion, 'name');
        $this->url = ResponseHelper::getStringProperty($promotion, 'url');
        $this->imageUrl = ResponseHelper::getStringProperty($promotion, 'imageUrl');
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

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }
}
