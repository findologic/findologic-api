<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Query
{
    /** @var Limit $limit */
    private $limit;

    /** @var QueryString $queryString */
    private $queryString;

    /** @var string|null $didYouMeanQuery */
    private $didYouMeanQuery;

    /** @var OriginalQuery|null $originalQuery */
    private $originalQuery = null;

    public function __construct(SimpleXMLElement $response)
    {
        $this->limit = new Limit($response->limit->attributes());
        $this->queryString = new QueryString($response->queryString);
        $this->didYouMeanQuery = ResponseHelper::getStringProperty($response, 'didYouMeanQuery');
        if ($response->originalQuery) {
            $this->originalQuery = new OriginalQuery($response->originalQuery);
        }
    }

    /**
     * @return Limit
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return QueryString
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * @return string|null
     */
    public function getDidYouMeanQuery()
    {
        return $this->didYouMeanQuery;
    }

    /**
     * @return OriginalQuery
     */
    public function getOriginalQuery()
    {
        return $this->originalQuery;
    }

    /**
     * This is a helper method. For more details check out our wiki.
     * @see https://github.com/findologic/findologic-api/wiki/Response-Helper-methods
     *
     * Will return the didYouMeanQuery if it was set, otherwise the value of the queryString is returned.
     * @return string
     */
    public function getAlternativeQuery()
    {
        if ($this->didYouMeanQuery) {
            return $this->didYouMeanQuery;
        }

        return $this->queryString->getValue();
    }
}
