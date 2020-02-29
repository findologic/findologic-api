<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Values\FilterValue;

abstract class Filter
{
    const
        FILTER_TYPE_SELECT = 'select',
        FILTER_TYPE_LABEL = 'label',
        FILER_TYPE_RANGE = 'range-slider',
        FILTER_TYPE_COLOR = 'color',
        FILTER_TYPE_IMAGE = 'image';

    /** @var string */
    protected $name;

    /** @var string */
    protected $displayName;

    /** @var string */
    protected $selectMode;

    /** @var string|null */
    protected $cssClass;

    /** @var string|null */
    protected $noAvailableFiltersText;

    /** @var string|null */
    protected $combinationOperation;

    /** @var FilterValue[] */
    protected $values = [];

    public function __construct(array $filter)
    {
        $this->name = ResponseHelper::getStringProperty($filter, 'name');
        $this->displayName = ResponseHelper::getStringProperty($filter, 'displayName');
        $this->selectMode = ResponseHelper::getStringProperty($filter, 'selectMode');
        $this->cssClass = ResponseHelper::getStringProperty($filter, 'cssClass');
        $this->noAvailableFiltersText = ResponseHelper::getStringProperty($filter, 'noAvailableFiltersText');
        $this->combinationOperation = ResponseHelper::getStringProperty($filter, 'combinationOperation');

        if (!isset($filter['values'])) {
            return;
        }

        foreach ($filter['values'] as $filterValue) {
            $this->values[] = FilterValue::getInstance($this, $filterValue);
        }
    }

    public static function getInstance(array $filter)
    {
        $filterType = ResponseHelper::getStringProperty($filter, 'type');

        switch ($filterType) {
            case self::FILTER_TYPE_SELECT:
                return new SelectFilter($filter);
            case self::FILER_TYPE_RANGE:
                return new RangeSliderFilter($filter);
            case self::FILTER_TYPE_COLOR:
                return new ColorFilter($filter);
            case self::FILTER_TYPE_IMAGE:
                return new ImageFilter($filter);
            case self::FILTER_TYPE_LABEL:
            default:
                return new LabelFilter($filter);
        }
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
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @return string
     */
    public function getSelectMode()
    {
        return $this->selectMode;
    }

    /**
     * @return string|null
     */
    public function getCssClass()
    {
        return $this->cssClass;
    }

    /**
     * @return string|null
     */
    public function getNoAvailableFiltersText()
    {
        return $this->noAvailableFiltersText;
    }

    /**
     * @return string|null
     */
    public function getCombinationOperation()
    {
        return $this->combinationOperation;
    }

    /**
     * @return FilterValue[]
     */
    public function getValues()
    {
        return $this->values;
    }
}
