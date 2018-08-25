<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use SimpleXMLElement;

class Query
{
    /** @var Limit $limit */
    private $limit;

    /** @var QueryString $queryString */
    private $queryString;

    /** @var string $didYouMeanQuery */
    private $didYouMeanQuery;

    /** @var OriginalQuery $originalQuery */
    private $originalQuery;

    /** @var int $searchWordCount */
    private $searchWordCount;

    /** @var int $foundWordsCount */
    private $foundWordsCount;

    /**
     * Query constructor.
     * @param SimpleXMLElement $response
     */
    public function __construct($response)
    {
        $this->limit = new Limit($response->limit->attributes());
        $this->queryString = new QueryString($response->queryString);
        $this->didYouMeanQuery = (string)$response->didYouMeanQuery;
        $this->originalQuery = new OriginalQuery($response->originalQuery);
        $this->searchWordCount = (int)$response->searchWordCount;
        $this->foundWordsCount = (int)$response->foundWordsCount;
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
     * @return string
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
     * @return int
     */
    public function getSearchWordCount()
    {
        return $this->searchWordCount;
    }

    /**
     * @return int
     */
    public function getFoundWordsCount()
    {
        return $this->foundWordsCount;
    }
}
