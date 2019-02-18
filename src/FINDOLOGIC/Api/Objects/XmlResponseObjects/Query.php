<?php

namespace FINDOLOGIC\Api\Objects\XmlResponseObjects;

use SimpleXMLElement;

class Query
{
    /** @var Limit $limit */
    private $limit;

    /** @var QueryString $queryString */
    private $queryString;

    /** @var string $didYouMeanQuery */
    private $didYouMeanQuery;

    /** @var OriginalQuery|null $originalQuery */
    private $originalQuery;

    /** @var int $searchedWordsCount */
    private $searchedWordsCount;

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
        if ($response->originalQuery) {
            $this->originalQuery = new OriginalQuery($response->originalQuery);
        } else {
            $this->originalQuery = null;
        }
        $this->searchedWordsCount = (int)$response->searchedWordCount;
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
    public function getSearchedWordsCount()
    {
        return $this->searchedWordsCount;
    }

    /**
     * @return int
     */
    public function getFoundWordsCount()
    {
        return $this->foundWordsCount;
    }
}
