<?php

namespace FINDOLOGIC\Api\ResponseObjects;

use FINDOLOGIC\Api\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Api\RequestBuilders\Autocomplete\SuggestRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\RequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml20\NavigationRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml20\SearchRequestBuilder;
use FINDOLOGIC\Api\ResponseObjects\Autocomplete\SuggestResponse;
use FINDOLOGIC\Api\ResponseObjects\Xml20\Xml20Response;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use InvalidArgumentException;

abstract class Response
{
    const STATUS_OK = 200;
    const SERVICE_ALIVE_BODY = 'alive';

    /** @var float|null */
    protected $responseTime;

    /**
     * @param string $response Raw response as string.
     * @param null $responseTime Response time in microseconds.
     */
    abstract public function __construct($response, $responseTime = null);

    /**
     * Builds a new Response instance based on the given request builder.
     *
     * @param RequestBuilder $requestBuilder
     * @param GuzzleResponse $response
     * @param GuzzleResponse|null $alivetestResponse The alivetest response, or null if no alivetest was made.
     * @param float|null $responseTime
     * @return Response
     */
    public static function buildInstance(
        RequestBuilder $requestBuilder,
        GuzzleResponse $response,
        $alivetestResponse = null,
        $responseTime = null
    ) {
        if ($alivetestResponse !== null) {
            self::checkAlivetestBody($alivetestResponse);
        }
        self::checkResponseIsValid($response);

        switch (get_class($requestBuilder)) {
            case SearchRequestBuilder::class:
            case NavigationRequestBuilder::class:
                return new Xml20Response($response->getBody()->getContents(), $responseTime);
            case SuggestRequestBuilder::class:
                return new SuggestResponse($response->getBody()->getContents(), $responseTime);
            default:
                throw new InvalidArgumentException(sprintf(
                    'Unknown request builder: %s',
                    get_class($requestBuilder)
                ));
        }
    }

    /**
     * Gets the response time that FINDOLOGIC took to respond to the request in microseconds. Please note that this
     * time also includes latency, etc.
     *
     * @return float|null
     */
    public function getResponseTime()
    {
        return $this->responseTime;
    }

    /**
     * Checks if the response is valid. If not, an exception will be thrown.
     *
     * @param GuzzleResponse $response
     */
    protected static function checkResponseIsValid($response)
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode !== self::STATUS_OK) {
            throw new ServiceNotAliveException(sprintf('Unexpected status code %s.', $statusCode));
        }
    }

    /**
     * @param GuzzleResponse $response
     */
    protected static function checkAlivetestBody($response)
    {
        $alivetestContents = $response->getBody()->getContents();
        if ($alivetestContents !== self::SERVICE_ALIVE_BODY) {
            throw new ServiceNotAliveException($alivetestContents);
        }
    }
}
