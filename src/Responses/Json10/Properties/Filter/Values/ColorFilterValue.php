<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Values;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class ColorFilterValue extends FilterValue
{
    /** @var string */
    protected $color;

    /** @var string */
    protected $image;

    public function __construct(array $filterValue)
    {
        parent::__construct($filterValue);

        $this->image = ResponseHelper::getStringProperty($filterValue, 'image');
        $this->color = ResponseHelper::getStringProperty($filterValue, 'color');
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}
