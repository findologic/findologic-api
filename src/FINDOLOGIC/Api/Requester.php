<?php

namespace FINDOLOGIC\Api;

use FINDOLOGIC\Api\Exceptions\ConfigException;
use FINDOLOGIC\Api\RequestBuilders\Json\SuggestionRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\RequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\NavigationRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\SearchRequestBuilder;
use InvalidArgumentException;

abstract class Requester
{
    const
        SEARCH_REQUEST = 0,
        NAVIGATION_REQUEST = 1,
        SUGGESTION_REQUEST = 2;

    /**
     * @param Requester::SEARCH_REQUEST|Requester::NAVIGATION_REQUEST|Requester::SUGGESTION_REQUEST $type The type of
     * the request to choose.
     * @param array $config Containing the necessary config.
     *      $config = [
     *          FindologicConfig::SHOPKEY            => (string) Service's shopkey. Required.
     *          FindologicConfig::API_URL            => (string) Findologic API URL. Optional.
     *          FindologicConfig::ALIVETEST_TIMEOUT  => (float) Timeout for an alivetest in seconds. Optional.
     *          FindologicConfig::REQUEST_TIMEOUT    => (float) Timeout for a request in seconds. Optional.
     *          FindologicConfig::HTTP_CLIENT        => (GuzzleHttp\Client) Client that is used for requests. Optional.
     *     ]
     *
     * @return RequestBuilder The requestBuilder
     * @throws InvalidArgumentException if the type is unknown.
     * @throws ConfigException if the config is not valid.
     */
    public static function getRequestBuilder($type, $config)
    {
        $findologicConfig = new Config($config);

        switch ($type) {
            case self::SEARCH_REQUEST:
                return new SearchRequestBuilder($findologicConfig);
            case self::NAVIGATION_REQUEST:
                return new NavigationRequestBuilder($findologicConfig);
            case self::SUGGESTION_REQUEST:
                return new SuggestionRequestBuilder($findologicConfig);
            default:
                throw new InvalidArgumentException('Unknown request type.');
        }
    }
}
