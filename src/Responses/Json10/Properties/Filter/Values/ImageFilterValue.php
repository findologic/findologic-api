<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Values;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class ImageFilterValue extends FilterValue
{
    /** @var string */
    protected $image;

    public function __construct(array $filterValue)
    {
        parent::__construct($filterValue);

        $this->image = ResponseHelper::getStringProperty($filterValue, 'image');
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}
