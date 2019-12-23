<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Metadata
{
    /** @var Landingpage|null */
    private $landingPage;

    /** @var Promotion|null */
    private $promotion;

    /** @var string|null */
    private $searchConcept;

    /** @var string */
    private $requestId;

    /** @var string */
    private $effectiveQuery;

    /** @var int */
    private $totalResults;

    public function __construct(array $metadata)
    {
        $this->landingPage = ResponseHelper::castTo($metadata, 'landingpage', Landingpage::class);
        $this->promotion = ResponseHelper::castTo($metadata, 'promotion', Promotion::class);
        $this->searchConcept = ResponseHelper::getStringProperty($metadata, 'searchConcept');
        $this->requestId = ResponseHelper::getStringProperty($metadata, 'requestId');
        $this->effectiveQuery = ResponseHelper::getStringProperty($metadata, 'effectiveQuery');
        $this->totalResults = ResponseHelper::getIntProperty($metadata, 'totalResults');
    }

    /**
     * @return Landingpage|null
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
     * @return string|null
     */
    public function getSearchConcept()
    {
        return $this->searchConcept;
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @return string
     */
    public function getEffectiveQuery()
    {
        return $this->effectiveQuery;
    }

    /**
     * @return int
     */
    public function getTotalResults()
    {
        return $this->totalResults;
    }
}