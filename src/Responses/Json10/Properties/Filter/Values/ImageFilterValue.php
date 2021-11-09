<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Values;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class ImageFilterValue extends FilterValue
{
    protected string $image;

    public function __construct(array $filterValue)
    {
        parent::__construct($filterValue);

        $this->image = ResponseHelper::getStringProperty($filterValue, 'image');
    }

    public function getImage(): string
    {
        return $this->image;
    }
}
