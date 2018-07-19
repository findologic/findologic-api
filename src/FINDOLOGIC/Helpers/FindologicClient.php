<?php

namespace FINDOLOGIC\Helpers;

use FINDOLOGIC\Exceptions\ServiceNotAliveException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FindologicClient
{
    /** FINDOLOGIC API URL that is used for each request. */
    const FINDOLOGIC_API_URL = 'https://service.findologic.com/ps/%s/%s';
    /**
     * FINDOLOGIC alivetest file. It is used to determine if a service can answer requests.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#fallback_mechanism
     */
    const FINDOLOGIC_ALIVETEST_ACTION = 'alivetest.php';

    /**
     * Timeout in seconds. If the response takes longer than the timeout, an exception is thrown. Make sure to catch it
     * to have a working fallback mechanism.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#fallback_mechanism
     */
    const FINDOLOGIC_ALIVETEST_TIMEOUT = 1;
    const FINDOLOGIC_RESPONSE_TIMEOUT = 3;

    const SEARCH_ACTION = 'search.php';
    const NAVIGATION_ACTION = 'navigation.php';
    const SUGGEST_ACTION = 'suggest.php';

    public $shopkey;
    public $apiUrl;
    public $alivetestTimeout;
    public $requestTimeout;
    public $httpClient;

    public $shopurl;

    public function __construct($shopkey, $apiUrl, $alivetestTimeout, $requestTimeout, $httpClient)
    {
        $this->shopkey = $shopkey;
        // Set defaults if they are not explicitly set.
        $this->apiUrl = $apiUrl ?: self::FINDOLOGIC_API_URL;
        $this->alivetestTimeout = $alivetestTimeout ?: self::FINDOLOGIC_ALIVETEST_TIMEOUT;
        $this->requestTimeout = $requestTimeout ?: self::FINDOLOGIC_RESPONSE_TIMEOUT;
        $this->httpClient = $httpClient ?: new Client();
    }

    public function search($params)
    {
        $this->shopurl = $params['shopurl'];
        if ($this->isAlive()) {
            //TODO: Do a search request with given params.
        }
    }

    public function navigate($params)
    {
        if ($this->isAlive()) {
            //TODO: Do a navigation request with given params.
        }
    }

    public function suggest($params)
    {
        if ($this->isAlive()) {
            //TODO: Do a suggestion request with given params.
        }
    }

    /**
     * Checks weither the service is alive or not.
     *
     * @return bool returns true if the alivetest was successful.
     *
     * @throws ServiceNotAliveException if the alivetest was not successful.
     */
    private function isAlive()
    {
        $alivetestUrl = $this->getAlivetestUrl();

        try {
            $request = $this->httpClient->request('GET', $alivetestUrl, ['timeout' => $this->alivetestTimeout]);
        } catch (GuzzleException $e) {
            $request = null;
        }

        if ($request !== null && $request->getStatusCode() == 200 && $request->getBody() == 'alive') {
            return true;
        }

        throw new ServiceNotAliveException();
    }

    private function getAlivetestUrl()
    {
        return sprintf(self::FINDOLOGIC_API_URL, $this->shopurl, self::FINDOLOGIC_ALIVETEST_ACTION) .
            '?' . http_build_query(['shopkey' => $this->shopkey]);
    }

    private function getUrlByRequestType()
    {
        //TODO: Fix that.
        sprintf(self::FINDOLOGIC_API_URL, $this->shopkey, $this->action);
    }
}