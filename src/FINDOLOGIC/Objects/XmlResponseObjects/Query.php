<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use FINDOLOGIC\Helpers\ResponseHelper;
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
    private $originalQuery;

    /** @var int $searchedWordCount */
    private $searchedWordCount;

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
        $this->didYouMeanQuery = ResponseHelper::getProperty($response, 'didYouMeanQuery', 'string');
        if ($response->originalQuery) {
            $this->originalQuery = new OriginalQuery($response->originalQuery);
        } else {
            $this->originalQuery = null;
        }
        $this->searchedWordCount = ResponseHelper::getProperty($response, 'searchedWordCount', 'int', true);
        $this->foundWordsCount = ResponseHelper::getProperty($response, 'foundWordsCount', 'int', true);
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
     * @return int
     */
    public function getSearchedWordCount()
    {
        return $this->searchedWordCount;
    }

    /**
     * @return int
     */
    public function getFoundWordsCount()
    {
        return $this->foundWordsCount;
    }
}
