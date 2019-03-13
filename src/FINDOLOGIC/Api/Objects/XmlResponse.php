<?php

namespace FINDOLOGIC\Api\Objects;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use FINDOLOGIC\Api\Objects\XmlResponseObjects\Filter;
use FINDOLOGIC\Api\Objects\XmlResponseObjects\Landingpage;
use FINDOLOGIC\Api\Objects\XmlResponseObjects\Product;
use FINDOLOGIC\Api\Objects\XmlResponseObjects\Promotion;
use FINDOLOGIC\Api\Objects\XmlResponseObjects\Query;
use FINDOLOGIC\Api\Objects\XmlResponseObjects\Results;
use FINDOLOGIC\Api\Objects\XmlResponseObjects\Servers;
use SimpleXMLElement;

/**
 * Is used for search and navigation requests with XML response only!
 */
class XmlResponse
{
    /** @var Servers $servers */
    private $servers;

    /** @var Query $query */
    private $query;

    /** @var Landingpage|null $landingPage */
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

    /** @var float|null */
    private $responseTime;

    /**
     * XmlResponse constructor.
     * @param string $response
     * @param float|null $responseTime
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
     * If the response contains a Landingpage, it will be returned, otherwise return null.
     *
     * @param SimpleXMLElement $xmlResponse
     * @return null|Landingpage
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
     * @return null|Promotion
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
     * @return Landingpage
     */
    public function getLandingPage()
    {
        return $this->landingPage;
    }

    /**
     * @return Promotion
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

    /**
     * @return float|null
     */
    public function getResponseTime()
    {
        return $this->responseTime;
    }
}
