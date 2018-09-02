<?php

namespace FINDOLOGIC\Objects;

use FINDOLOGIC\Objects\XmlResponseObjects\Filter;
use FINDOLOGIC\Objects\XmlResponseObjects\Landingpage;
use FINDOLOGIC\Objects\XmlResponseObjects\Product;
use FINDOLOGIC\Objects\XmlResponseObjects\Promotion;
use FINDOLOGIC\Objects\XmlResponseObjects\Query;
use FINDOLOGIC\Objects\XmlResponseObjects\Results;
use FINDOLOGIC\Objects\XmlResponseObjects\Servers;
use SimpleXMLElement;

/**
 * Is used for search and navigation requests with XML response only!
 *
 * Class XmlResponse
 * @package FINDOLOGIC\Objects
 */
class XmlResponse
{
    /** @var Servers $servers */
    private $servers;

    /** @var Query $query */
    private $query;

    /** @var Landingpage $landingPage */
    private $landingPage;

    /** @var Promotion $promotion */
    private $promotion;

    /** @var Results $results */
    private $results;

    /** @var Product[] $products */
    private $products;

    /** @var Filter[] $filters */
    private $filters;

    /**
     * XmlResponse constructor.
     * @param $response
     */
    public function __construct($response)
    {
        $xmlResponse = new SimpleXMLElement($response);

        $this->servers = new Servers($xmlResponse->servers[0]);
        $this->query = new Query($xmlResponse->query[0]);
        $this->landingPage = new Landingpage($xmlResponse->landingPage[0]->attributes());
        $this->promotion = new Promotion($xmlResponse->promotion[0]->attributes());
        $this->results = new Results($xmlResponse->results[0]);

        foreach ($xmlResponse->products->children() as $product) {
            $productId = (string)$product->attributes()->id;
            // Set product ids as keys for the products.
            $this->products[$productId] = new Product($product);
        }

        foreach ($xmlResponse->filters->children() as $filter) {
            $filterName = (string)$filter->name;
            // Set filter names as keys for the filters.
            $this->filters[$filterName] = new Filter($filter);
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
}
