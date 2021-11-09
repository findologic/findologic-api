<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Values;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class ColorFilterValue extends FilterValue
{
    protected ?string $color;
    protected ?string $image;

    /**
     * @param array<string, string|null> $filterValue
     */
    public function __construct(array $filterValue)
    {
        parent::__construct($filterValue);

        $this->image = ResponseHelper::getStringProperty($filterValue, 'image');
        $this->color = ResponseHelper::getStringProperty($filterValue, 'color');
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}
