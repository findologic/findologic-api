<?php

namespace FINDOLOGIC\Api\Responses;

use FINDOLOGIC\Api\Definitions\OutputAdapter;
use FINDOLOGIC\Api\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Api\Requests\Autocomplete\SuggestRequest;
use FINDOLOGIC\Api\Requests\Item\ItemUpdateRequest;
use FINDOLOGIC\Api\Requests\Request;
use FINDOLOGIC\Api\Requests\SearchNavigation\SearchNavigationRequest;
use FINDOLOGIC\Api\Responses\Autocomplete\SuggestResponse;
use FINDOLOGIC\Api\Responses\Html\GenericHtmlResponse;
use FINDOLOGIC\Api\Responses\Item\ItemUpdateResponse;
use FINDOLOGIC\Api\Responses\Json10\Json10Response;
use FINDOLOGIC\Api\Responses\Xml21\Xml21Response;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as GuzzleResponse;

abstract class Response
{
    public const STATUS_OK = 200;
    public const SERVICE_ALIVE_BODY = 'alive';

    protected ?float $responseTime;
    protected string $rawResponse;

    public function __construct(string $response, ?float $responseTime = null)
    {
        $this->rawResponse = $response;
        $this->responseTime = $responseTime;

        $this->buildResponseElementInstances($response);
    }

    /**
     * Builds the response instances for all classes for the current response.
     */
    abstract protected function buildResponseElementInstances(string $response): void;

    /**
     * Builds a new Response instance based on the given Request.
     */
    public static function buildInstance(
        Request $request,
        GuzzleResponse $response,
        ?GuzzleResponse $alivetestResponse = null,
        ?float $responseTime = null
    ): Response {
        if ($alivetestResponse !== null) {
            self::checkAlivetestBody($alivetestResponse);
        }
        self::checkResponseIsValid($response);

        switch (true) {
            case $request instanceof SearchNavigationRequest:
                return self::buildSearchOrNavigationResponse(
                    $request,
                    $response->getBody()->getContents(),
                    $responseTime
                );
            case $request instanceof SuggestRequest:
                return new SuggestResponse($response->getBody()->getContents(), $responseTime);
            case $request instanceof ItemUpdateRequest:
                return new ItemUpdateResponse($response->getBody()->getContents(), $responseTime);
            default:
                throw new InvalidArgumentException(sprintf(
                    'Unknown Request: %s',
                    get_class($request)
                ));
        }
    }

    public function getRawResponse(): string
    {
        return $this->rawResponse;
    }

    /**
     * Gets the response time that FINDOLOGIC took to respond to the request in microseconds. Please note that this
     * time also includes latency, etc.
     */
    public function getResponseTime(): ?float
    {
        return $this->responseTime;
    }

    private static function buildSearchOrNavigationResponse(
        Request $request,
        string $responseContents,
        ?float $responseTime
    ): Response {
        switch ($request->getOutputAdapter()) {
            case OutputAdapter::JSON_10:
                return new Json10Response($responseContents, $responseTime);
            case OutputAdapter::XML_21:
                return new Xml21Response($responseContents, $responseTime);
            case OutputAdapter::HTML_20:
            case OutputAdapter::HTML_30:
            case OutputAdapter::HTML_31:
                return new GenericHtmlResponse($responseContents, $responseTime);
            default:
                throw new InvalidArgumentException(sprintf(
                    'Unknown or invalid outputAdapter "%s".',
                    $request->getOutputAdapter()
                ));
        }
    }

    /**
     * Checks if the response is valid. If not, an exception will be thrown.
     */
    protected static function checkResponseIsValid(GuzzleResponse $response): void
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode !== self::STATUS_OK) {
            throw new ServiceNotAliveException(sprintf('Unexpected status code %s.', $statusCode));
        }
    }

    /**
     * @param GuzzleResponse $response
     */
    protected static function checkAlivetestBody(GuzzleResponse $response): void
    {
        $alivetestContents = $response->getBody()->getContents();
        if ($alivetestContents !== self::SERVICE_ALIVE_BODY) {
            throw new ServiceNotAliveException($alivetestContents);
        }
    }
}
