<?php

namespace FINDOLOGIC\Api;

use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Api\RequestBuilders\AlivetestRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Json\SuggestRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\RequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\NavigationRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\SearchRequestBuilder;
use FINDOLOGIC\Api\ResponseObjects\Json\SuggestResponse;
use FINDOLOGIC\Api\ResponseObjects\Xml\XmlResponse;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;

class Client
{
    const METHOD_GET = 'GET';
    const STATUS_OK = 200;
    const SERVICE_ALIVE_BODY = 'alive';

    /** @var Config */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
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

        $requestStart = microtime(true);
        $response = $this->sendRequest($requestBuilder);
        $responseTime = microtime(true) - $requestStart;

        $this->checkResponseIsValid($response);

        switch (get_class($requestBuilder)) {
            case SearchRequestBuilder::class:
            case NavigationRequestBuilder::class:
                return new XmlResponse($response->getBody()->getContents(), $responseTime);
            case SuggestRequestBuilder::class:
                return new SuggestResponse($response->getBody()->getContents(), $responseTime);
            default:
                throw new InvalidArgumentException('Unknown request builder');
        }
    }

    /**
     * @param RequestBuilder $requestBuilder
     * @return Response
     * @throws ServiceNotAliveException If the request was not successful.
     */
    private function sendRequest(RequestBuilder $requestBuilder)
    {
        $requestTimeout = $this->config->getRequestTimeout();
        if (get_class($requestBuilder) === AlivetestRequestBuilder::class) {
            $requestTimeout = $this->config->getAlivetestTimeout();
        }

        try {
            return $this->config->getHttpClient()->request(
                self::METHOD_GET,
                $this->buildRequestUrl($requestBuilder),
                ['connect_timeout' => $requestTimeout]
            );
        } catch (GuzzleException $e) {
            throw new ServiceNotAliveException($e->getMessage());
        }
    }

    /**
     * Will do an alivetest if the given request builder requires one. An alivetest may be done if the request is a
     * search or a navigation request.
     *
     * @param RequestBuilder $requestBuilder
     */
    private function doAlivetest(RequestBuilder $requestBuilder)
    {
        switch (get_class($requestBuilder)) {
            case NavigationRequestBuilder::class:
            case SearchRequestBuilder::class:
                $this->sendRequest(new AlivetestRequestBuilder());
                break;
            default:
                break;
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
}
