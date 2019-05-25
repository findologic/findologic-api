<?php

namespace FINDOLOGIC\Api\ResponseObjects\Xml20\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Attributes
{
    const MIN_RANGE = 'min';
    const MAX_RANGE = 'max';

    /** @var array $selectedRange */
    private $selectedRange;

    /** @var array $totalRange */
    private $totalRange;

    /** @var float $stepSize */
    private $stepSize;

    /** @var string $unit */
    private $unit;

    /**
     * Attributes constructor.
     * @param SimpleXMLElement $response
     */
    public function __construct($response)
    {
        $this->selectedRange = [
            self::MIN_RANGE => ResponseHelper::getFloatProperty($response->selectedRange, 'min'),
            self::MAX_RANGE => ResponseHelper::getFloatProperty($response->selectedRange, 'max'),
        ];

        $this->totalRange = [
            self::MIN_RANGE => ResponseHelper::getFloatProperty($response->totalRange, 'min'),
            self::MAX_RANGE => ResponseHelper::getFloatProperty($response->totalRange, 'max'),
        ];

        $this->stepSize = ResponseHelper::getFloatProperty($response, 'stepSize');
        $this->unit = ResponseHelper::getStringProperty($response, 'unit');
    }

    /**
     * @return array
     */
    public function getSelectedRange()
    {
        return $this->selectedRange;
    }

    /**
     * @return array
     */
    public function getTotalRange()
    {
        return $this->totalRange;
    }

    /**
     * @return float
     */
    public function getStepSize()
    {
        return $this->stepSize;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }
}
