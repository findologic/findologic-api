<?php

namespace FINDOLOGIC\Api;

use FINDOLOGIC\Api\Exceptions\ConfigException;
use FINDOLOGIC\Api\RequestBuilders\Json\SuggestionRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\NavigationRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\SearchRequestBuilder;

class FindologicApi
{
    /** @var FindologicConfig */
    private $config;

     /**
      * FindologicApi constructor.
      *
      * @param array $config containing the necessary config.
      *      $config = [
      *          FindologicConfig::SHOPKEY            => (string) Service's shopkey. Required.
      *          FindologicConfig::API_URL            => (string) Findologic API URL. Optional.
      *          FindologicConfig::ALIVETEST_TIMEOUT  => (float) Timeout for an alivetest in seconds. Optional.
      *          FindologicConfig::REQUEST_TIMEOUT    => (float) Timeout for a request in seconds. Optional.
      *          FindologicConfig::HTTP_CLIENT        => (GuzzleHttp\Client) Client that is used for requests. Optional.
      *     ]
      * @throws ConfigException if the config is not valid.
      */
    public function __construct($config)
    {
        $this->config = new FindologicConfig($config);
    }

    /**
     * @return SearchRequestBuilder
     */
    public function createSearchRequest()
    {
        return new SearchRequestBuilder($this->config);
    }

    /**
     * @return NavigationRequestBuilder
     */
    public function createNavigationRequest()
    {
        return new NavigationRequestBuilder($this->config);
    }

    /**
     * @return SuggestionRequestBuilder
     */
    public function createSuggestionRequest()
    {
        return new SuggestionRequestBuilder($this->config);
    }

    /**
     * @return FindologicConfig
     */
    public function getConfig()
    {
        return $this->config;
    }
}
