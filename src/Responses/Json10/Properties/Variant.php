<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Variant
{
    /** @var string */
    private $name;

    /** @var string|null */
    private $correctedQuery;

    /** @var string|null */
    private $improvedQuery;

    /** @var string|null */
    private $didYouMeanQuery;

    public function __construct(array $variant)
    {
        $this->name = ResponseHelper::getStringProperty($variant, 'name');
        $this->correctedQuery = ResponseHelper::getStringProperty($variant, 'correctedQuery');
        $this->improvedQuery = ResponseHelper::getStringProperty($variant, 'improvedQuery');
        $this->didYouMeanQuery = ResponseHelper::getStringProperty($variant, 'didYouMeanQuery');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getCorrectedQuery()
    {
        return $this->correctedQuery;
    }

    /**
     * @return string|null
     */
    public function getImprovedQuery()
    {
        return $this->improvedQuery;
    }

    /**
     * @return string|null
     */
    public function getDidYouMeanQuery()
    {
        return $this->didYouMeanQuery;
    }
}
