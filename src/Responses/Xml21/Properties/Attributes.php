<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Definitions\Defaults;
use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Attributes
{
    private Range $selectedRange;
    private Range $totalRange;
    private float $stepSize;
    private string $unit;

    public function __construct(SimpleXMLElement $response)
    {
        $this->stepSize = ResponseHelper::getFloatProperty($response, 'stepSize') ?? Defaults::RANGE_STEP_SIZE;
        $this->unit = ResponseHelper::getStringProperty($response, 'unit') ?? Defaults::CURRENCY;

        if ($response->selectedRange) {
            $this->selectedRange = new Range($response->selectedRange);
        }

        if ($response->totalRange) {
            $this->totalRange = new Range($response->totalRange);
        }
    }

    public function getSelectedRange(): Range
    {
        return $this->selectedRange;
    }

    public function getTotalRange(): Range
    {
        return $this->totalRange;
    }

    public function getStepSize(): float
    {
        return $this->stepSize;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }
}
