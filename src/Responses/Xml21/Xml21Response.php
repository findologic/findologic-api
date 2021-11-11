<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Xml21;

use FINDOLOGIC\Api\Exceptions\ParseException;
use FINDOLOGIC\Api\Helpers\ResponseHelper;
use FINDOLOGIC\Api\Responses\Response;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Filter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\LandingPage;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Product;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Promotion;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Query;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Results;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Servers;
use SimpleXMLElement;

class Xml21Response extends Response
{
    private Servers $servers;
    private Query $query;
    private ?LandingPage $landingPage;
    private ?Promotion $promotion;
    private Results $results;
    /** @var Product[] $products */
    private array $products = [];
    /** @var Filter[] $mainFilters */
    private array $mainFilters = [];
    /** @var Filter[] $otherFilters */
    private array $otherFilters = [];
    private bool $hasMainFilters = false;
    private bool $hasOtherFilters = false;
    private int $mainFilterCount = 0;
    private int $otherFilterCount = 0;

    protected function buildResponseElementInstances(string $response): void
    {
        $xmlResponse = new SimpleXMLElement($response);

        if (!$xmlResponse->servers[0] || !$xmlResponse->query[0] || !$xmlResponse->results[0]) {
            throw new ParseException('Could not parse XML_2.1 response');
        }

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

        foreach ($xmlResponse->filters->main->children() as $filter) {
            $filterName = ResponseHelper::getStringProperty($filter, 'name');
            // Set filter names as keys for the filters.
            $this->mainFilters[$filterName] = Filter::getInstance($filter);
            $this->hasMainFilters = true;
            $this->mainFilterCount++;
        }

        foreach ($xmlResponse->filters->other->children() as $filter) {
            $filterName = ResponseHelper::getStringProperty($filter, 'name');
            // Set filter names as keys for the filters.
            $this->otherFilters[$filterName] = Filter::getInstance($filter);
            $this->hasOtherFilters = true;
            $this->otherFilterCount++;
        }
    }

    /**
     * If the response contains a LandingPage, it will be returned, otherwise return null.
     */
    private function getLandingPageFromResponse(SimpleXMLElement $xmlResponse): ?LandingPage
    {
        if ($xmlResponse->landingPage && $xmlResponse->landingPage[0] && $xmlResponse->landingPage[0]->attributes()) {
            return new LandingPage($xmlResponse->landingPage[0]->attributes());
        }

        return null;
    }

    /**
     * If the response contains a Promotion, it will be returned, otherwise return null.
     */
    private function getPromotionFromResponse(SimpleXMLElement $xmlResponse): ?Promotion
    {
        if ($xmlResponse->promotion && $xmlResponse->promotion[0] && $xmlResponse->promotion[0]->attributes()) {
            return new Promotion($xmlResponse->promotion[0]->attributes());
        }

        return null;
    }

    public function getServers(): Servers
    {
        return $this->servers;
    }

    public function getQuery(): Query
    {
        return $this->query;
    }

    public function getLandingPage(): ?LandingPage
    {
        return $this->landingPage;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function getResults(): Results
    {
        return $this->results;
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return Filter[]
     */
    public function getMainFilters(): array
    {
        return $this->mainFilters;
    }

    /**
     * @return Filter[]
     */
    public function getOtherFilters(): array
    {
        return $this->otherFilters;
    }

    public function hasMainFilters(): bool
    {
        return $this->hasMainFilters;
    }

    public function hasOtherFilters(): bool
    {
        return $this->hasOtherFilters;
    }

    public function getMainFilterCount(): int
    {
        return $this->mainFilterCount;
    }

    public function getOtherFilterCount(): int
    {
        return $this->otherFilterCount;
    }
}
