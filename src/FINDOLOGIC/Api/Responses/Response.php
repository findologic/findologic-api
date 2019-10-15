<?php

namespace FINDOLOGIC\Api\Responses;

use DOMDocument;
use FINDOLOGIC\Api\Definitions\OutputAdapter;
use FINDOLOGIC\Api\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Api\Requests\Autocomplete\SuggestRequest;
use FINDOLOGIC\Api\Requests\Request;
use FINDOLOGIC\Api\Requests\SearchNavigation\NavigationRequest;
use FINDOLOGIC\Api\Requests\SearchNavigation\SearchNavigationRequest;
use FINDOLOGIC\Api\Requests\SearchNavigation\SearchRequest;
use FINDOLOGIC\Api\Responses\Autocomplete\SuggestResponse;
use FINDOLOGIC\Api\Responses\Html\GenericHtmlResponse;
use FINDOLOGIC\Api\Responses\Xml20\Xml20Response;
use FINDOLOGIC\Api\Responses\Xml21\Xml21Response;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use InvalidArgumentException;

abstract class Response
{
    const STATUS_OK = 200;
    const SERVICE_ALIVE_BODY = 'alive';

    /** @var float|null */
    protected $responseTime;

    /** @var string */
    protected $rawResponse;

    /**
     * @param string $response Raw response as string.
     * @param float|null $responseTime Response time in microseconds.
     */
    public function __construct($response, $responseTime = null)
    {
        $this->rawResponse = $response;
        $this->responseTime = $responseTime;

        $this->buildResponseElementInstances($response);
    }

    /**
     * Builds the response instances for all classes for the current response.
     *
     * @param $response
     */
    abstract protected function buildResponseElementInstances($response);

    /**
     * Builds a new Response instance based on the given Request.
     *
     * @param Request $request
     * @param GuzzleResponse $response
     * @param GuzzleResponse|null $alivetestResponse The alivetest response, or null if no alivetest was made.
     * @param float|null $responseTime
     * @return Response
     */
    public static function buildInstance(
        Request $request,
        GuzzleResponse $response,
        $alivetestResponse = null,
        $responseTime = null
    ) {
        if ($alivetestResponse !== null) {
            self::checkAlivetestBody($alivetestResponse);
        }
        self::checkResponseIsValid($request, $response);

        switch (true) {
            case $request instanceof SearchNavigationRequest:
                return self::buildSearchOrNavigationResponse(
                    $request,
                    $response->getBody()->getContents(),
                    $responseTime
                );
            case $request instanceof SuggestRequest:
                return new SuggestResponse($response->getBody()->getContents(), $responseTime);
            default:
                throw new InvalidArgumentException(sprintf(
                    'Unknown Request: %s',
                    get_class($request)
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
     * @param Request $request
     * @param string $responseContents
     * @param float|null $responseTime
     * @return Response
     */
    private static function buildSearchOrNavigationResponse(
        Request $request,
        $responseContents,
        $responseTime
    ) {
        switch ($request->getOutputAdapter()) {
            case OutputAdapter::XML_20:
                return new Xml20Response($responseContents, $responseTime);
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
     *
     * @param Request $request
     * @param GuzzleResponse $response
     */
    protected static function checkResponseIsValid(Request $request, GuzzleResponse $response)
    {
        if ($request->getOutputAdapter() === OutputAdapter::XML_21) {
            $xml= new DOMDocument();
            $xml->loadXML($response->getBody()->getContents());

            if (!$xml->schemaValidate('../../../../vendor/findologic/xml-response-schema/schema.xsd')) {
                throw new ServiceNotAliveException(sprintf('The given response does not comply to the XML_2.1 schema.'));
            }
        }

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