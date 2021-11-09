<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Query
{
    private Limit $limit;
    private QueryString $queryString;
    private ?string $didYouMeanQuery;
    private ?OriginalQuery $originalQuery = null;

    public function __construct(SimpleXMLElement $response)
    {
        $this->limit = new Limit($response->limit->attributes());
        $this->queryString = new QueryString($response->queryString);
        $this->didYouMeanQuery = ResponseHelper::getStringProperty($response, 'didYouMeanQuery');
        if ($response->originalQuery) {
            $this->originalQuery = new OriginalQuery($response->originalQuery);
        }
    }

    public function getLimit(): Limit
    {
        return $this->limit;
    }

    public function getQueryString(): QueryString
    {
        return $this->queryString;
    }

    public function getDidYouMeanQuery(): ?string
    {
        return $this->didYouMeanQuery;
    }

    public function getOriginalQuery(): ?OriginalQuery
    {
        return $this->originalQuery;
    }

    /**
     * This is a helper method. For more details check out our wiki.
     * @see https://github.com/findologic/findologic-api/wiki/Response-Helper-methods
     *
     * Will return the didYouMeanQuery if it was set, otherwise the value of the queryString is returned.
     */
    public function getAlternativeQuery(): string
    {
        if ($this->didYouMeanQuery) {
            return $this->didYouMeanQuery;
        }

        return $this->queryString->getValue();
    }
}
