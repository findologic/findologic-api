<?php

namespace FINDOLOGIC\Api\Responses\Xml21;

use Exception;
use FINDOLOGIC\Api\Helpers\ResponseHelper;
use FINDOLOGIC\Api\Responses\Response;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\LandingPage;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Product;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Promotion;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Query;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Results;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Servers;
use SimpleXMLElement;

class Xml21Response extends Response
{
    /** @var Servers $servers */
    private $servers;

    /** @var Query $query */
    private $query;

    /** @var LandingPage|null $landingPage */
    private $landingPage;

    /** @var Promotion|null $promotion */
    private $promotion;

    /** @var Results $results */
    private $results;

    /** @var Product[] $products */
    private $products = [];

    /** @var Filter[] $mainFilters */
    private $mainFilters = [];

    /** @var Filter[] $otherFilters */
    private $otherFilters = [];

    /** @var bool $hasMainFilters */
    private $hasMainFilters = false;

    /** @var bool $hasMainFilters */
    private $hasOtherFilters = false;

    /** @var int $mainFilterCount */
    private $mainFilterCount = 0;

    /** @var int $otherFilterCount */
    private $otherFilterCount = 0;

    protected function buildResponseElementInstances($response)
    {
        $xmlResponse = new SimpleXMLElement($response);

        $this->servers = new Servers($xmlResponse->servers[0]);
        $this->query = new Query($xmlResponse->query[0]);

        $this->landingPage = $this->getLandingPageFromResponse($xmlResponse);
        $this->promotion = $this->getPromotionFromResponse($xmlResponse);
        $this->results = new Results($xmlResponse->results[0]);

        try {
            foreach ($xmlResponse->products->children() as $product) {
                $productId = ResponseHelper::getStringProperty($product->attributes(), 'id', true);
                // Set product ids as keys for the products.
                $this->products[$productId] = new Product($product);
            }
        } catch (Exception $ignored) {
            // Do nothing if an exception is thrown as it does not need to be executed on error
        }

        try {
            foreach ($xmlResponse->filters->main->children() as $filter) {
                $filterName = ResponseHelper::getStringProperty($filter, 'name');
                // Set filter names as keys for the filters.
                $this->mainFilters[$filterName] = new Filter($filter);
                $this->hasMainFilters = true;
                $this->mainFilterCount++;
            }
        } catch (Exception $ignored) {
            // Do nothing if an exception is thrown as it does not need to be executed on error
        }

        try {
            foreach ($xmlResponse->filters->other->children() as $filter) {
                $filterName = ResponseHelper::getStringProperty($filter, 'name');
                // Set filter names as keys for the filters.
                $this->otherFilters[$filterName] = new Filter($filter);
                $this->hasOtherFilters = true;
                $this->otherFilterCount++;
            }
        } catch (Exception $ignored) {
            // Do nothing if an exception is thrown as it does not need to be executed on error
        }

    }

    /**
     * If the response contains a LandingPage, it will be returned, otherwise return null.
     *
     * @param SimpleXMLElement $xmlResponse
     * @return LandingPage|null
     */
    private function getLandingPageFromResponse(SimpleXMLElement $xmlResponse)
    {
        if ($xmlResponse->landingPage) {
            return new LandingPage($xmlResponse->landingPage[0]->attributes());
        }

        return null;
    }

    /**
     * If the response contains a Promotion, it will be returned, otherwise return null.
     *
     * @param SimpleXMLElement $xmlResponse
     * @return Promotion|null
     */
    private function getPromotionFromResponse(SimpleXMLElement $xmlResponse)
    {
        if ($xmlResponse->promotion) {
            return new Promotion($xmlResponse->promotion[0]->attributes());
        }

        return null;
    }

    /**
     * @return Servers
     */
    public function getServers()
    {
        return $this->servers;
    }

    /**
     * @return Query
     */
    public function getQuery()
    {
        return $this->query;
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
     * @return Results
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @return Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return Filter[]
     */
    public function getMainFilters()
    {
        return $this->mainFilters;
    }

    /**
     * @return Filter[]
     */
    public function getOtherFilters()
    {
        return $this->otherFilters;
    }

    /**
     * @return bool
     */
    public function hasMainFilters()
    {
        return $this->hasMainFilters;
    }

    /**
     * @return bool
     */
    public function hasOtherFilters()
    {
        return $this->hasOtherFilters;
    }

    /**
     * @return int
     */
    public function getMainFilterCount()
    {
        return $this->mainFilterCount;
    }

    /**
     * @return int
     */
    public function getOtherFilterCount()
    {
        return $this->otherFilterCount;
    }
}
