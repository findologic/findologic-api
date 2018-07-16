<?php

namespace Request;

use Request\ParameterBuilder\ParameterBuilder;

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

    const NAVIGATION_FILE = 'selector.php';
    const SEARCH_FILE = 'index.php';

    const TYPE_SEARCH = 0;
    const TYPE_NAVIGATION = 1;

    /**
     * @param $type int decides if it is a search or a navigation request. Use available constants for that.
     */
    public function send($type)
    {
        //TODO: Send the request. Make sure that the timeout will be respected.
    }
}