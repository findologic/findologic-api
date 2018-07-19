<?php

namespace FINDOLOGIC\Request;

use FINDOLOGIC\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Helpers\SendRequestHelper;
use FINDOLOGIC\Request\Parameters\ParameterBuilder;
use FINDOLOGIC\Request\Parameters\ParameterValidator;
use FINDOLOGIC\Request\Requests\NavigationRequest;
use FINDOLOGIC\Request\Requests\SearchRequest;
use FINDOLOGIC\Request\Requests\SuggestRequest;
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

    /**
     * Timeout in seconds. If the response takes longer than the timeout, an exception is thrown. Make sure to catch it
     * to have a working fallback mechanism.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#fallback_mechanism
     */
    const FINDOLOGIC_ALIVETEST_TIMEOUT = 1;
    const FINDOLOGIC_RESPONSE_TIMEOUT = 3;

    const TYPE_SEARCH = 0;
    const TYPE_NAVIGATION = 1;
    const TYPE_SUGGEST = 2;

    private $action;
    private $shopkey;

    /**
     * @param $type int Decides if it is a search or a navigation request. Use available constants for that.
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
            case self::TYPE_SUGGEST:
                $request = new SuggestRequest();
                break;
            default:
                throw new \InvalidArgumentException('Unsupported request type.');
        }
        return $request;
    }

    /**
     * Sends the request with all set params and makes sure that all required params are in place. It respects the
     * alivetest, alivetest timeout and search timeout.
     *
     * @throws ServiceNotAliveException
     */
    public function send()
    {
        $rawParams = $this->getParam();
        ParameterValidator::requiredParamsAreSet($rawParams);
        $this->shopkey = $this->getParam('shopkey');

        $alivetestUrl = $this->getAlivetestUrl();

        SendRequestHelper::sendAlivetest($alivetestUrl, self::FINDOLOGIC_ALIVETEST_TIMEOUT);

        $requestUrl = $this->getUrlByRequestType();
        //TODO: Send the request. Make sure that the timeout will be respected.
    }

    private function getAlivetestUrl()
    {
        return sprintf(self::FINDOLOGIC_API_URL, $this->shopkey, self::FINDOLOGIC_ALIVETEST_ACTION);
    }

    private function getUrlByRequestType()
    {
        sprintf(self::FINDOLOGIC_API_URL, $this->shopkey, $this->action);
    }
}
