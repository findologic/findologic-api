<?php

namespace FINDOLOGIC\Api\ResponseObjects\Xml;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use FINDOLOGIC\Api\ResponseObjects\Response;
use FINDOLOGIC\Api\ResponseObjects\Xml\Properties\Filter;
use FINDOLOGIC\Api\ResponseObjects\Xml\Properties\LandingPage;
use FINDOLOGIC\Api\ResponseObjects\Xml\Properties\Product;
use FINDOLOGIC\Api\ResponseObjects\Xml\Properties\Promotion;
use FINDOLOGIC\Api\ResponseObjects\Xml\Properties\Query;
use FINDOLOGIC\Api\ResponseObjects\Xml\Properties\Results;
use FINDOLOGIC\Api\ResponseObjects\Xml\Properties\Servers;
use SimpleXMLElement;

/**
 * Is used for search and navigation requests with XML response only!
 */
class XmlResponse extends Response
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
    private $products;

    /** @var Filter[] $filters */
    private $filters;

    /** @var bool $hasFilters */
    private $hasFilters = false;

    /** @var int $filterAmount */
    private $filterAmount = 0;

    /**
     * @inheritdoc
     */
    public function __construct($response, $responseTime = null)
    {
        $this->responseTime = $responseTime;
        $xmlResponse = new SimpleXMLElement($response);

        $this->servers = new Servers($xmlResponse->servers[0]);
        $this->query = new Query($xmlResponse->query[0]);

        $this->landingPage = $this->getLandingPageFromResponse($xmlResponse);
        $this->promotion = $this->getPromotionFromResponse($xmlResponse);
        $this->results = new Results($xmlResponse->results[0]);

        foreach ($xmlResponse->products->children() as $product) {
            $productId = ResponseHelper::getStringProperty($product->attributes(), 'id', true);
            // Set product ids as keys for the products.
            $this->products[$productId] = new Product($product);
        }

        foreach ($xmlResponse->filters->children() as $filter) {
            $filterName =  ResponseHelper::getStringProperty($filter, 'name');
            // Set filter names as keys for the filters.
            $this->filters[$filterName] = new Filter($filter);
            $this->hasFilters = true;
            $this->filterAmount++;
        }
    }

    /**
     * If the response contains a LandingPage, it will be returned, otherwise return null.
     *
     * @param SimpleXMLElement $xmlResponse
     * @return LandingPage|null
     */
    private function getLandingPageFromResponse($xmlResponse)
    {
        if ($xmlResponse->landingPage) {
            return new LandingPage($xmlResponse->landingPage[0]->attributes());
        } else {
            return null;
        }
    }

    /**
     * If the response contains a Promotion, it will be returned, otherwise return null.
     *
     * @param SimpleXMLElement $xmlResponse
     * @return Promotion|null
     */
    private function getPromotionFromResponse($xmlResponse)
    {
        if ($xmlResponse->promotion) {
            return new Promotion($xmlResponse->promotion[0]->attributes());
        } else {
            return null;
        }
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
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return bool
     */
    public function hasFilters()
    {
        return $this->hasFilters;
    }

    /**
     * @return int
     */
    public function getFilterCount()
    {
        return $this->filterAmount;
    }
}
