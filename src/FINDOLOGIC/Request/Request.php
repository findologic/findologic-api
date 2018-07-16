<?php

namespace FINDOLOGIC\Request;

use FINDOLOGIC\Request\ParameterBuilder\ParameterBuilder;
use FINDOLOGIC\Request\Requests\NavigationRequest\NavigationRequest;
use FINDOLOGIC\Request\Requests\SearchRequest\SearchRequest;

class Request extends ParameterBuilder
{
    /** FINDOLOGIC API URL that is used for each request. */
    const FINDOLOGIC_API_URL = 'https://service.findologic.com/ps/%s/%s';

    /**
     * FINDOLOGIC alivetest file. It is used to determine if a service can answer requests.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#fallback_mechanism
     */
    const FINDOLOGIC_ALIVETEST_FILE = 'alivetest.php';

    const FINDOLOGIC_ALIVETEST_TIMEOUT_MS = 1000;

    /**
     * If the response takes longer than the timeout, an exception is thrown. Make sure to catch it to have a working
     * fallback mechanism.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#fallback_mechanism
     */
    const FINDOLOGIC_RESPONSE_TIMEOUT_MS = 3000;

    const TYPE_SEARCH = 0;
    const TYPE_NAVIGATION = 1;

    /**
     * @param $type int decides if it is a search or a navigation request. Use available constants for that.
     * @return NavigationRequest|SearchRequest
     */
    public static function create($type)
    {
        switch ($type) {
            case self::TYPE_SEARCH:
                $exporter = new SearchRequest();
                break;
            case self::TYPE_NAVIGATION:
                $exporter = new NavigationRequest();
                break;
            default:
                throw new \InvalidArgumentException('Unsupported request type.');
        }
        return $exporter;
    }

    /**
     *
     */
    public function send()
    {
        //TODO: Send the request. Make sure that the timeout will be respected.
    }
}