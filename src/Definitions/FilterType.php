<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Definitions;

class FilterType extends Definition
{
    public const SELECT = 'select';
    public const LABEL = 'label';
    public const RANGE_SLIDER = 'range-slider';
    public const COLOR = 'color';
    public const COLOR_ALTERNATIVE = 'color-picker';
    public const VENDOR_IMAGE = 'image';
    public const VENDOR_IMAGE_ALTERNATIVE = 'image-filter';
}
