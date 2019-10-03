<?php

namespace FINDOLOGIC\Api;

use FINDOLOGIC\Api\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Api\Requests\AlivetestRequest;
use FINDOLOGIC\Api\Requests\Request;
use FINDOLOGIC\Api\Requests\SearchNavigation\NavigationRequest;
use FINDOLOGIC\Api\Requests\SearchNavigation\SearchRequest;
use FINDOLOGIC\Api\Responses\Response;
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
     * @param Request $request
     * @return Response
     */
    public function send(Request $request)
    {
        $request->checkRequiredParamsAreSet();
        $alivetestResponse = $this->doAlivetest($request);

        $requestStart = microtime(true);
        $response = $this->sendRequest($request);
        $responseTime = microtime(true) - $requestStart;

        return Response::buildInstance($request, $response, $alivetestResponse, $responseTime);
    }

    /**
     * @param Request $request
     * @return GuzzleResponse
     * @throws ServiceNotAliveException If the request was not successful.
     */
    private function sendRequest(Request $request)
    {
        $requestTimeout = $this->config->getRequestTimeout();
        if (get_class($request) === AlivetestRequest::class) {
            $requestTimeout = $this->config->getAlivetestTimeout();
        }

        try {
            return $this->config->getHttpClient()->request(
                self::METHOD_GET,
                $request->buildRequestUrl($this->config),
                ['connect_timeout' => $requestTimeout]
            );
        } catch (GuzzleException $e) {
            throw new ServiceNotAliveException($e->getMessage());
        }
    }

    /**
     * Will do an alivetest if the given Request requires one. An alivetest may be done if the Request is a
     * SearchRequest/NavigationRequest.
     *
     * @param Request $request
     * @return GuzzleResponse|null
     */
    private function doAlivetest(Request $request)
    {
        switch (get_class($request)) {
            case NavigationRequest::class:
            case SearchRequest::class:
                // We need to make sure that the alivetest uses the same parameters as the request itself.
                $alivetestRequest = new AlivetestRequest();
                $alivetestRequest->setParams($request->getParams());
                return $this->sendRequest($alivetestRequest);
            default:
                return null;
        }
    }
}
