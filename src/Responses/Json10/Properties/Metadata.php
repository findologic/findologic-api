<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Metadata
{
    /** @var LandingPage|null */
    private $landingPage;

    /** @var Promotion|null */
    private $promotion;

    /** @var string */
    private $searchConcept;

    /** @var int */
    private $totalResults;

    /** @var string */
    private $currencySymbol;

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

    /**
     * @return LandingPage|null
     */
    public function getLandingPage()
    {
        return $this->landingPage;
    }

    /**
     * @return Promotion|null
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * @return string
     */
    public function getSearchConcept()
    {
        return $this->searchConcept;
    }

    /**
     * @return int
     */
    public function getTotalResults()
    {
        return $this->totalResults;
    }

    /**
     * @return string
     */
    public function getCurrencySymbol()
    {
        return $this->currencySymbol;
    }
}
