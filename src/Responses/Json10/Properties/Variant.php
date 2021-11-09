<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Variant
{
    private string $name;
    private ?string $correctedQuery;
    private ?string $improvedQuery;
    private ?string $didYouMeanQuery;

    public function __construct(array $variant)
    {
        $this->name = ResponseHelper::getStringProperty($variant, 'name');
        $this->correctedQuery = ResponseHelper::getStringProperty($variant, 'correctedQuery');
        $this->improvedQuery = ResponseHelper::getStringProperty($variant, 'improvedQuery');
        $this->didYouMeanQuery = ResponseHelper::getStringProperty($variant, 'didYouMeanQuery');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCorrectedQuery(): ?string
    {
        return $this->correctedQuery;
    }

    public function getImprovedQuery(): ?string
    {
        return $this->improvedQuery;
    }

    public function getDidYouMeanQuery(): ?string
    {
        return $this->didYouMeanQuery;
    }
}
