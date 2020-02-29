<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Values;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class ColorFilterValue extends FilterValue
{
    /** @var string */
    protected $color;

    public function __construct(array $filterValue)
    {
        parent::__construct($filterValue);

        $this->color = ResponseHelper::getStringProperty($filterValue, 'color');
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }
}
