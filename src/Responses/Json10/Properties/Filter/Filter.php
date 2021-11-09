<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Values\FilterValue;

abstract class Filter
{
    public const FILTER_TYPE_SELECT = 'select';
    public const FILTER_TYPE_LABEL = 'label';
    public const FILER_TYPE_RANGE = 'range-slider';
    public const FILTER_TYPE_COLOR = 'color';
    public const FILTER_TYPE_IMAGE = 'image';

    protected string $name;
    protected string $displayName;
    protected string $selectMode;
    protected ?string $cssClass;
    protected ?string $noAvailableFiltersText;
    protected ?string $combinationOperation;
    /** @var FilterValue[] */
    protected array $values = [];

    /**
     * @param array<string, array<string|int|float|bool|null>|string|null> $filter
     */
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

    /**
     * @param array<string, array<string|int|float|bool|null>|string|null> $filter
     * @return Filter
     */
    public static function getInstance(array $filter): Filter
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function getSelectMode(): string
    {
        return $this->selectMode;
    }

    public function getCssClass(): ?string
    {
        return $this->cssClass;
    }

    public function getNoAvailableFiltersText(): ?string
    {
        return $this->noAvailableFiltersText;
    }

    public function getCombinationOperation(): ?string
    {
        return $this->combinationOperation;
    }

    /**
     * @return FilterValue[]
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
