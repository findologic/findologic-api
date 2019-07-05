<?php

namespace FINDOLOGIC\Api;

use FINDOLOGIC\Api\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Api\RequestBuilders\AlivetestRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\RequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\NavigationRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\SearchRequestBuilder;
use FINDOLOGIC\Api\ResponseObjects\Response;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

class Client
{
    const METHOD_GET = 'GET';

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
     * @return Response
     */
    public function send(RequestBuilder $requestBuilder)
    {
        $requestBuilder->checkRequiredParamsAreSet();
        $alivetestResponse = $this->doAlivetest($requestBuilder);

        $requestStart = microtime(true);
        $response = $this->sendRequest($requestBuilder);
        $responseTime = microtime(true) - $requestStart;

        return Response::buildInstance($requestBuilder, $response, $alivetestResponse, $responseTime);
    }

    /**
     * @param RequestBuilder $requestBuilder
     * @return GuzzleResponse
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
                $requestBuilder->buildRequestUrl($this->config),
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
     * @return GuzzleResponse|null
     */
    private function doAlivetest(RequestBuilder $requestBuilder)
    {
        switch (get_class($requestBuilder)) {
            case NavigationRequestBuilder::class:
            case SearchRequestBuilder::class:
                // We need to make sure that the alivetest uses the same parameters as the request itself.
                $alivetestRequestBuilder = new AlivetestRequestBuilder();
                $alivetestRequestBuilder->setParams($requestBuilder->getParams());
                return $this->sendRequest($alivetestRequestBuilder);
            default:
                return null;
        }
    }
}
