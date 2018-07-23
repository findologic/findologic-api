<?php

namespace FINDOLOGIC\Request;

use FINDOLOGIC\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Helpers\FindologicClient;
use FINDOLOGIC\Request\Parameters\ParameterBuilder;
use FINDOLOGIC\Request\Parameters\ParameterValidator;
use FINDOLOGIC\Request\Requests\NavigationRequest;
use FINDOLOGIC\Request\Requests\SearchRequest;
use FINDOLOGIC\Request\Requests\SuggestRequest;
use InvalidArgumentException;

abstract class Request extends ParameterBuilder
{
    const TYPE_SEARCH = 0;
    const TYPE_NAVIGATION = 1;
    const TYPE_SUGGEST = 2;

    /**
     * @param $type int Decides if it is a search, navigation or suggest request. Use available constants for that.
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
                throw new InvalidArgumentException('Unsupported request type.');
        }
        return $request;
    }

    /**
     * Sends the request with all set params and makes sure that all required params are in place. It respects the
     * alivetest, alivetest timeout and search timeout.
     *
     * @param string $apiUrl If not set, will be set in FindologicClient. Convention is https://example.com/%s/%s
     *      since the first string will be the shopurl and the second one the action e.g. abc.de/autocomplete.php.
     * @param null $alivetestTimeout
     * @param null $requestTimeout
     * @param null|\GuzzleHttp\Client $httpClient
     *
     * @throws ServiceNotAliveException Catch this exception to have a valid fallback.
     */
    public function send($apiUrl = null, $alivetestTimeout = null, $requestTimeout = null, $httpClient = null)
    {
        $params = $this->getParam();
        ParameterValidator::requiredParamsAreSet($params);
        $findologicClient = new FindologicClient(
            $params,
            $apiUrl,
            $alivetestTimeout,
            $requestTimeout,
            $httpClient
        );
        switch ($this->getAction()) {
            case FindologicClient::SEARCH_ACTION:
                $findologicClient->search();
                break;
            case FindologicClient::NAVIGATION_ACTION:
                $findologicClient->navigate();
                break;
            case FindologicClient::SUGGEST_ACTION:
                $findologicClient->suggest();
                break;
            default:
                throw new InvalidArgumentException('Unsupported action type.');
        }
    }

    public function getAction()
    {
        // This method is overridden by the request types.
    }
}
