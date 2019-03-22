<?php

namespace FINDOLOGIC\Api;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Api\RequestBuilders\Json\SuggestRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\RequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\NavigationRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\SearchRequestBuilder;
use FINDOLOGIC\Api\ResponseObjects\Json\SuggestResponse;
use FINDOLOGIC\Api\ResponseObjects\Xml\XmlResponse;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

class Client
{
    const METHOD_GET = 'GET';
    const STATUS_OK = 200;
    const SERVICE_ALIVE_BODY = 'alive';

    /** @var Config */
    private $config;

    /**
     * @var float|null Can be used to get the response time from the FINDOLOGIC API in microseconds.
     */
    private $responseTime = null;

    /**
     * @var float Saves the unix timestamp in microseconds of the last made request.
     */
    private $requestUnixTimestamp;

    public function __construct(Config $config)
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
     * Sends a request to FINDOLOGIC. An alivetest may be sent if the request is a search or a navigation request.
     *
     * @param RequestBuilder $requestBuilder
     * @return SuggestResponse|XmlResponse
     */
    public function send(RequestBuilder $requestBuilder)
    {
        $this->doAlivetest($requestBuilder);

        try {
            $response = $this->sendRequest($requestBuilder);
        } catch (GuzzleException $e) {
            throw new ServiceNotAliveException($e->getMessage());
        }

        $this->checkResponseIsValid($response);

        switch (get_class($requestBuilder)) {
            case SearchRequestBuilder::class:
            case NavigationRequestBuilder::class:
                return new XmlResponse($response->getBody()->getContents());
            case SuggestRequestBuilder::class:
                return new SuggestResponse($response->getBody()->getContents());
            default:
                throw new InvalidArgumentException('Unknown request');
        }
    }

    /**
     * @param RequestBuilder $requestBuilder
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     */
    private function sendRequest(RequestBuilder $requestBuilder)
    {
        return $this->config->getHttpClient()->request(
            self::METHOD_GET,
            $this->buildRequestUrl($requestBuilder),
            ['connect_timeout' => $this->config->getRequestTimeout()]
        );
    }

    /**
     * Will do an alivetest. An alivetest is only done if the request is a search or a navigation request.
     *
     * @param RequestBuilder $requestBuilder
     */
    private function doAlivetest(RequestBuilder $requestBuilder)
    {
        switch (get_class($requestBuilder)) {
            case NavigationRequestBuilder::class:
            case SearchRequestBuilder::class:
                try {
                    $this->sendAlivetestRequest($requestBuilder);
                } catch (GuzzleException $e) {
                    throw new ServiceNotAliveException($e->getMessage());
                }
                break;
            default:
                break;
        }
    }

    /**
     * Sends an alivetest request.
     *
     * @param RequestBuilder $requestBuilder
     * @throws GuzzleException
     */
    private function sendAlivetestRequest(RequestBuilder $requestBuilder)
    {
        $response =  $this->config->getHttpClient()->request(
            self::METHOD_GET,
            $this->buildAlivetestUrl($requestBuilder),
            ['connect_timeout' => $this->config->getAlivetestTimeout()]
        );

        $responseContent = $response->getBody()->getContents();
        $responseBodyIsAlive = $responseContent === self::SERVICE_ALIVE_BODY;

        if (!$responseBodyIsAlive) {
            throw new ServiceNotAliveException($responseContent);
        }
    }

    /**
     * Builds the request URL based on the set params.
     *
     * @param RequestBuilder $requestBuilder
     * @return string
     */
    private function buildRequestUrl(RequestBuilder $requestBuilder)
    {
        $params = $requestBuilder->getParams();

        $shopUrl = $params[QueryParameter::SHOP_URL];
        // If the shopkey was not manually overridden, we take the shopkey from the config.
        if (!isset($params[QueryParameter::SERVICE_ID])) {
            $params['shopkey'] = $this->config->getServiceId();
        }
        $queryParams = http_build_query($params);
        // Removes indexes from query params. E.g. attrib[0] will be attrib[].
        $fullQueryString = preg_replace('/%5B\d+%5D/', '%5B%5D', $queryParams);

        $apiUrl = sprintf($this->config->getApiUrl(), $shopUrl, $requestBuilder->getEndpoint());
        return sprintf('%s?%s', $apiUrl, $fullQueryString);
    }

    /**
     * Builds the alivetest URL.
     *
     * @param RequestBuilder $requestBuilder
     * @return string
     */
    public function buildAlivetestUrl(RequestBuilder $requestBuilder)
    {
        $params = $requestBuilder->getParams();

        $shopUrl = $params[QueryParameter::SHOP_URL];
        // If the shopkey was not manually overridden, we take the shopkey from the config.
        if (!isset($params[QueryParameter::SERVICE_ID])) {
            $params['shopkey'] = $this->config->getServiceId();
        }
        $queryString = http_build_query([
            QueryParameter::SERVICE_ID => $params[QueryParameter::SERVICE_ID]
        ]);

        $apiUrl = sprintf($this->config->getApiUrl(), $shopUrl, Endpoint::ALIVETEST);
        return sprintf('%s?%s', $apiUrl, $queryString);
    }

    /**
     * Checks if the response is valid. If not, an exception will be thrown.
     *
     * @param Response $response
     */
    private function checkResponseIsValid($response)
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode !== self::STATUS_OK) {
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
