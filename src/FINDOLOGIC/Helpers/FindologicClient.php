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
     * Timeout in seconds. If the alivetest takes longer than the timeout, an exception is thrown. Make sure to catch it
     * to have a working fallback mechanism.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#fallback_mechanism
     */
    const FINDOLOGIC_ALIVETEST_TIMEOUT = 1;

    /**
     * Timeout in seconds. If the response takes longer than the timeout, an exception is thrown. Make sure to catch it
     * to have a working fallback mechanism.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#fallback_mechanism
     */
    const FINDOLOGIC_RESPONSE_TIMEOUT = 3;

    const SEARCH_ACTION = 'index.php';
    const NAVIGATION_ACTION = 'selector.php';
    const SUGGEST_ACTION = 'autocomplete.php';

    const GET_METHOD = 'GET';

    private $params;
    private $apiUrl;
    private $alivetestTimeout;
    private $requestTimeout;
    private $httpClient;

    public function __construct($params, $apiUrl, $alivetestTimeout, $requestTimeout, $httpClient)
    {
        $this->params = $params;
        // Set defaults if they are not explicitly set.
        $this->apiUrl = $apiUrl ?: self::FINDOLOGIC_API_URL;
        $this->alivetestTimeout = $alivetestTimeout ?: self::FINDOLOGIC_ALIVETEST_TIMEOUT;
        $this->requestTimeout = $requestTimeout ?: self::FINDOLOGIC_RESPONSE_TIMEOUT;
        $this->httpClient = $httpClient ?: new Client();
    }

    /**
     * @throws ServiceNotAliveException
     */
    public function search()
    {
        $action = FindologicClient::SEARCH_ACTION;
        if ($this->isAlive()) {
            $requestUrl = $this->buildRequestUrlByAction($action);

            try {
                $request = $this->httpClient->request(
                    self::GET_METHOD,
                    $requestUrl,
                    ['timeout' => $this->requestTimeout]
                );
            } catch (GuzzleException $e) {
                $request = null;
            }

            if ($request !== null && $request->getStatusCode() === 200) {
                return $request->getBody();
            }
            //TODO: Do a search request with given params.
        }
    }

    /**
     * @throws ServiceNotAliveException
     */
    public function navigate()
    {
        $action = FindologicClient::NAVIGATION_ACTION;
        if ($this->isAlive()) {
            $requestUrl = $this->buildRequestUrlByAction($action);

            try {
                $request = $this->httpClient->request(
                    self::GET_METHOD,
                    $requestUrl,
                    ['timeout' => $this->requestTimeout]
                );
            } catch (GuzzleException $e) {
                $request = null;
            }

            if ($request !== null && $request->getStatusCode() === 200) {
                return $request->getBody();
            }
            //TODO: Do a navigation request with given params.
        }
    }

    /**
     * @throws ServiceNotAliveException
     */
    public function suggest()
    {
        $action = FindologicClient::SUGGEST_ACTION;
        if ($this->isAlive()) {
            $requestUrl = $this->buildRequestUrlByAction($action);

            try {
                $request = $this->httpClient->request(
                    self::GET_METHOD,
                    $requestUrl,
                    ['timeout' => $this->requestTimeout]
                );
            } catch (GuzzleException $e) {
                $request = null;
            }

            if ($request !== null && $request->getStatusCode() === 200) {
                return $request->getBody();
            }
            //TODO: Do a suggestion request with given params.
        }
    }

    /**
     * Checks whether the service is alive or not. Returns true or throws an exception if the service is not alive.
     *
     * @return bool
     * @throws ServiceNotAliveException
     */
    private function isAlive()
    {
        $alivetestUrl = $this->buildAlivetestUrl();

        try {
            $request = $this->httpClient->request(
                self::GET_METHOD,
                $alivetestUrl,
                ['timeout' => $this->alivetestTimeout]
            );
        } catch (GuzzleException $e) {
            $request = null;
        }

        if ($request !== null && $request->getStatusCode() == 200 && $request->getBody() == 'alive') {
            return true;
        }

        throw new ServiceNotAliveException();
    }

    /**
     * @return string
     */
    private function buildAlivetestUrl()
    {
        $params = '?' . http_build_query(['shopkey' => $this->params['shopkey']]);
        $alivetestUrl = sprintf($this->apiUrl, $this->params['shopurl'], self::FINDOLOGIC_ALIVETEST_ACTION);
        return $alivetestUrl . $params;
    }

    /**
     * @param $action
     * @return string
     */
    private function buildRequestUrlByAction($action)
    {
        $apiUrl = $this->apiUrl;
        $rawParams = $this->params;
        $shopurl = $rawParams['shopurl'];

        $params = '?' . http_build_query($this->params);
        $requestUrl = sprintf($apiUrl, $shopurl, $action);
        return $requestUrl . $params;
    }
}
