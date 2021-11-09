<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Metadata
{
    private ?LandingPage $landingPage = null;
    private ?Promotion $promotion = null;
    private ?string $searchConcept;
    private int $totalResults;
    private string $currencySymbol;

    public function __construct(array $metadata)
    {
        if (isset($metadata['landingpage'])) {
            $this->landingPage = new LandingPage($metadata['landingpage']);
        }
        if (isset($metadata['promotion'])) {
            $this->promotion = new Promotion($metadata['promotion']);
        }

        $this->searchConcept = ResponseHelper::getStringProperty($metadata, 'searchConcept');
        $this->totalResults = ResponseHelper::getIntProperty($metadata, 'totalResults');
        $this->currencySymbol = ResponseHelper::getStringProperty($metadata, 'currencySymbol');
    }

    public function getLandingPage(): ?LandingPage
    {
        return $this->landingPage;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function getSearchConcept(): ?string
    {
        return $this->searchConcept;
    }

    public function getTotalResults(): int
    {
        return $this->totalResults;
    }

    public function getCurrencySymbol(): string
    {
        return $this->currencySymbol;
    }
}
