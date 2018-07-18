<?php

namespace FINDOLOGIC\Request;

use FINDOLOGIC\Request\Parameters\ParameterBuilder;
use FINDOLOGIC\Request\Parameters\ParameterValidator;
use FINDOLOGIC\Request\Requests\NavigationRequest;
use FINDOLOGIC\Request\Requests\SearchRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Requester extends ParameterBuilder
{
    /** FINDOLOGIC API URL that is used for each request. */
    const FINDOLOGIC_API_URL = 'https://service.findologic.com/ps/%s/%s';

    /**
     * FINDOLOGIC alivetest file. It is used to determine if a service can answer requests.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#fallback_mechanism
     */
    const FINDOLOGIC_ALIVETEST_ACTION = 'alivetest.php';

    const FINDOLOGIC_ALIVETEST_TIMEOUT_MS = 1000;

    /**
     * If the response takes longer than the timeout, an exception is thrown. Make sure to catch it to have a working
     * fallback mechanism.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#fallback_mechanism
     */
    const FINDOLOGIC_RESPONSE_TIMEOUT_MS = 3000;

    const TYPE_SEARCH = 0;
    const TYPE_NAVIGATION = 1;

    private $action;

    /**
     * @param $type int decides if it is a search or a navigation request. Use available constants for that.
     * @return NavigationRequest|SearchRequest
     */
    public static function create($type)
    {
        switch ($type) {
            case self::TYPE_SEARCH:
                $request = new SearchRequest();
                break;
            case self::TYPE_NAVIGATION:
                $request = new NavigationRequest();
                break;
            default:
                throw new \InvalidArgumentException('Unsupported request type.');
        }
        return $request;
    }

    /**
     * Sends the request with all set params and makes sure that all required params are in place. It respects the
     * alivetest, alivetest timeout and search timeout.
     */
    public function send()
    {
        $rawParams = $this->getParams();
        ParameterValidator::requiredParamsAreSet($rawParams);

        $alivetestUrl = sprintf(self::FINDOLOGIC_API_URL, $rawParams['shopurl'],
            self::FINDOLOGIC_ALIVETEST_ACTION);
        $searchUrl = sprintf(self::FINDOLOGIC_API_URL, $rawParams['shopurl'], $this->action);

        $client = new Client();
        try {
            $res = $client->request($alivetestUrl);
        } catch (GuzzleException $e) {
            $res = null;
            //TODO: Throw own exception but error message as above.
        }

        if ($res !== null && $res->getStatusCode() == 200) {
            echo $res->getBody();
        } else {
            //TODO: Throw exception that the service is not alive.
        }
        //TODO: Send the request. Make sure that the timeout will be respected.
    }
}
