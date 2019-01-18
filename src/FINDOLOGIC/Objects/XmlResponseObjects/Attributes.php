<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use FINDOLOGIC\Helpers\ResponseHelper;
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
            self::MIN_RANGE => ResponseHelper::getProperty($response->selectedRange, 'min', 'float'),
            self::MAX_RANGE => ResponseHelper::getProperty($response->selectedRange, 'max', 'float'),
        ];

        $this->totalRange = [
            self::MIN_RANGE => ResponseHelper::getProperty($response->totalRange, 'min', 'float'),
            self::MAX_RANGE => ResponseHelper::getProperty($response->totalRange, 'max', 'float'),
        ];

        $this->stepSize = ResponseHelper::getProperty($response, 'stepSize', 'float');
        $this->unit = ResponseHelper::getProperty($response, 'unit', 'string');
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
