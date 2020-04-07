<?php

namespace FINDOLOGIC\Api;

use Exception;
use FINDOLOGIC\Api\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Api\Requests\AlivetestRequest;
use FINDOLOGIC\Api\Requests\Request;
use FINDOLOGIC\Api\Requests\SearchNavigation\SearchNavigationRequest;
use FINDOLOGIC\Api\Responses\Response;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Message\Response as GuzzleResponse;

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
        /** @var GuzzleResponse $response */
        $response = $this->sendRequest($request);
        $responseTime = microtime(true) - $requestStart;

        return Response::buildInstance($request, $response, $alivetestResponse, $responseTime);
    }

    /**
     * @param Request $request
     * @return ResponseInterface
     * @throws ServiceNotAliveException If the request was not successful.
     */
    private function sendRequest(Request $request)
    {
        $requestTimeout = $this->config->getRequestTimeout();
        if ($request instanceof AlivetestRequest) {
            $requestTimeout = $this->config->getAlivetestTimeout();
        }

        try {
            return $this->config->getHttpClient()->get(
                $request->buildRequestUrl($this->config),
                ['connect_timeout' => $requestTimeout]
            );
        } catch (Exception $e) {
            throw new ServiceNotAliveException($e->getMessage());
        }
    }

    /**
     * Will do an alivetest if the given Request requires one. An alivetest may be done if the Request is a
     * SearchRequest/NavigationRequest.
     *
     * @param Request $request
     * @return ResponseInterface|null|void
     */
    private function doAlivetest(Request $request)
    {
        if ($request instanceof SearchNavigationRequest) {
            // We need to make sure that the alivetest uses the same parameters as the request itself.
            $alivetestRequest = new AlivetestRequest();
            $alivetestRequest->setParams($request->getParams());
            return $this->sendRequest($alivetestRequest);
        }
    }
}
