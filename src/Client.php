<?php

namespace FINDOLOGIC\Api;

use FINDOLOGIC\Api\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Api\Requests\AlivetestRequest;
use FINDOLOGIC\Api\Requests\Request;
use FINDOLOGIC\Api\Requests\SearchNavigation\SearchNavigationRequest;
use FINDOLOGIC\Api\Responses\Response;
use FINDOLOGIC\GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface as GuzzleResponse;

class Client
{
    /** @var Config */
    private $config;

    /**
     * @var bool Weither an alivetest was sent or not. Only one alivetest is sent per client lifetime.
     */
    private $alivetestSent = false;

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
        try {
            return $this->config->getHttpClient()->request(
                $request->getMethod(),
                $request->buildRequestUrl($this->config),
                $this->buildRequestOptions($request)
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
        if (!$request instanceof SearchNavigationRequest || $this->alivetestSent) {
            return null;
        }

        // We need to make sure that the alivetest uses the same parameters as the request itself.
        $alivetestRequest = new AlivetestRequest();
        $alivetestRequest->setParams($request->getParams());

        $response = $this->sendRequest($alivetestRequest);
        $this->alivetestSent = true;

        return $response;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function buildRequestOptions(Request $request)
    {
        $requestTimeout = $this->config->getRequestTimeout();
        if ($request instanceof AlivetestRequest) {
            $requestTimeout = $this->config->getAlivetestTimeout();
        }

        $options = [];
        $options['connect_timeout'] = $requestTimeout;

        if ($this->config->getAccessToken()) {
            $options['headers'] = [
                'Authorization' => 'Bearer ' . $this->config->getAccessToken()
            ];
        }

        return $options;
    }
}
