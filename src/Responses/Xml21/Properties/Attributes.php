<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Attributes
{
    /** @var Range $selectedRange */
    private $selectedRange;

    /** @var Range $totalRange */
    private $totalRange;

    /** @var float $stepSize */
    private $stepSize;

    /** @var string $unit */
    private $unit;

    public function __construct(SimpleXMLElement $response)
    {
        $this->stepSize = ResponseHelper::getFloatProperty($response, 'stepSize');
        $this->unit = ResponseHelper::getStringProperty($response, 'unit');

        if ($response->selectedRange) {
            $this->selectedRange = new Range($response->selectedRange);
        }

        if ($response->totalRange) {
            $this->totalRange = new Range($response->totalRange);
        }
    }

    /**
     * @return Range
     */
    public function getSelectedRange()
    {
        return $this->selectedRange;
    }

    /**
     * @return Range
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
