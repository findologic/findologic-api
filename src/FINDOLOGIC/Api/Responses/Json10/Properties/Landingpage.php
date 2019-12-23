<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Landingpage
{
    /** @var string */
    private $name;

    /** @var string */
    private $url;

    public function __construct(array $landingpage = null)
    {
        $this->name = ResponseHelper::getStringProperty($landingpage, 'name');
        $this->url = ResponseHelper::getStringProperty($landingpage, 'url');
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
