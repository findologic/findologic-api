<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api;

use FINDOLOGIC\Api\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Api\Requests\AlivetestRequest;
use FINDOLOGIC\Api\Requests\Request;
use FINDOLOGIC\Api\Requests\SearchNavigation\SearchNavigationRequest;
use FINDOLOGIC\Api\Responses\Response;
use FINDOLOGIC\GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as GuzzleResponse;

class Client
{
    private Config $config;

    /**
     * Weither an alivetest was sent or not. Only one alivetest is sent per client lifetime.
     */
    private bool $alivetestSent = false;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Sends a request to FINDOLOGIC. An alivetest may be sent if the request is a search or a navigation request.
     *
     * @param Request|RequestInterface $request Using the custom Findologic-API Request class instance is considered
     * deprecated and will be removed in version tag:v3.0.0.
     */
    public function send($request): Response
    {
        $request->checkRequiredParamsAreSet();
        $alivetestResponse = $this->doAlivetest($request);

        $requestStart = microtime(true);
        $response = $this->sendRequest($request);
        $responseTime = microtime(true) - $requestStart;

        return Response::buildInstance($request, $response, $alivetestResponse, $responseTime);
    }

    /**
     * Only call this method if you know what you are doing! It is highly recommended that an alivetest
     * is sent before a search/navigation request!
     */
    public function withoutAlivetest(): void
    {
        $this->alivetestSent = true;
    }

    /**
     * @throws ServiceNotAliveException If the request was not successful.
     */
    private function sendRequest(Request $request): GuzzleResponse
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
     */
    private function doAlivetest(Request $request): ?GuzzleResponse
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
     * @return array<string, array<string, string>|float>
     */
    private function buildRequestOptions(Request $request): array
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
