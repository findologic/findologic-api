<?php

namespace FINDOLOGIC\Api;

use FINDOLOGIC\Api\Exceptions\ConfigException;
use FINDOLOGIC\Api\RequestBuilders\Json\SuggestRequestBuilder;
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
     * @param Config $config Containing the necessary config.
     *
     * @return RequestBuilder The requestBuilder
     * @throws InvalidArgumentException if the type is unknown.
     */
    public static function getRequestBuilder($type, Config $config)
    {
        switch ($type) {
            case self::SEARCH_REQUEST:
                return new SearchRequestBuilder($config);
            case self::NAVIGATION_REQUEST:
                return new NavigationRequestBuilder($config);
            case self::SUGGESTION_REQUEST:
                return new SuggestRequestBuilder($config);
            default:
                throw new InvalidArgumentException('Unknown request type.');
        }
    }
}
