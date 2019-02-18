<?php

namespace FINDOLOGIC\Api;

use FINDOLOGIC\Api\Exceptions\ServiceNotAliveException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

class FindologicClient
{
    const GET_METHOD = 'GET';
    const STATUS_OK = 200;
    const SERVICE_ALIVE_BODY = 'alive';

    /** @var FindologicConfig */
    private $config;

    /**
     * @var float|null Can be used to get the response time from the FINDOLOGIC API in microseconds.
     */
    private $responseTime = null;

    /**
     * @var float Saves the unix timestamp in microseconds of the last made request.
     */
    private $requestUnixTimestamp;

    public function __construct(FindologicConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Gets the response time of the last made request. May return null if no request was made yet.
     *
     * @return float
     */
    public function getResponseTime()
    {
        return $this->responseTime;
    }

    /**
     * Requests an URL and checks the response for it's validity. A ServiceNotAliveException may be thrown if
     * the service is not alive or the status code is unexpected.
     *
     * @param string $url Requested URL.
     * @param bool $isAlivetest Flag to indicate an alivetest request.
     *
     * @return string Raw response data as string.
     */
    public function request($url, $isAlivetest = false)
    {
        $httpClient = $this->config->getHttpClient();

        try {
            $this->startResponseTime();
            $response = $httpClient->request(
                self::GET_METHOD,
                $url,
                ['connect_timeout' => $this->getRequestTimeout($isAlivetest)]
            );
        } catch (GuzzleException $e) {
            throw new ServiceNotAliveException($e->getMessage());
        }
        $this->endResponseTime();

        $this->checkResponseIsValid($response, $isAlivetest);
        return $response->getBody()->getContents();
    }

    /**
     * @param bool $isAlivetest
     * @return float
     */
    private function getRequestTimeout($isAlivetest)
    {
        if ($isAlivetest) {
            return $this->config->getAlivetestTimeout();
        } else {
            return $this->config->getRequestTimeout();
        }
    }

    /**
     * Checks if the response is valid. If not, an exception will be thrown.
     *
     * @param Response $response
     * @param bool $isAlivetest
     */
    private function checkResponseIsValid($response, $isAlivetest)
    {
        $statusCode = $response->getStatusCode();
        $responseContent = $response->getBody()->getContents();

        $responseBodyIsAlive = $responseContent === self::SERVICE_ALIVE_BODY;

        if ($isAlivetest) {
            if (!$responseBodyIsAlive) {
                throw new ServiceNotAliveException($responseContent);
            }
        } elseif ($statusCode !== self::STATUS_OK) {
            throw new ServiceNotAliveException(sprintf('Unexpected status code %s.', $statusCode));
        }
    }

    /**
     * Sets the unix timestamp in microseconds for the request.
     */
    private function startResponseTime()
    {
        $this->requestUnixTimestamp = microtime(true);
    }

    /**
     * Calculates how much time has been passed since the request has been made to
     * determine the full time duration for the request (in microseconds).
     */
    private function endResponseTime()
    {
        $requestEndTime = microtime(true);
        $this->responseTime = $requestEndTime - $this->requestUnixTimestamp;
    }
}
