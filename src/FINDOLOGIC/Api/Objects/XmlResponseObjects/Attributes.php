<?php

namespace FINDOLOGIC\Api\Objects\XmlResponseObjects;

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
            self::MIN_RANGE => (float)$response->selectedRange->min,
            self::MAX_RANGE => (float)$response->selectedRange->max
        ];

        $this->totalRange = [
            self::MIN_RANGE => (float)$response->totalRange->min,
            self::MAX_RANGE => (float)$response->totalRange->max
        ];

        $this->stepSize = (float)$response->stepSize;
        $this->unit = (string)$response->unit;
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
