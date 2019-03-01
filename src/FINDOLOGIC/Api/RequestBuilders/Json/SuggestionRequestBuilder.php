<?php

namespace FINDOLOGIC\Api\RequestBuilders\Json;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\FindologicConfig;
use FINDOLOGIC\Api\Objects\JsonResponse;
use FINDOLOGIC\Api\RequestBuilders\RequestBuilder;
use FINDOLOGIC\Api\Validators\ParameterValidator;

class SuggestionRequestBuilder extends RequestBuilder
{
    protected $endpoint = Endpoint::SUGGESTION;

    public function __construct(FindologicConfig $config)
    {
        parent::__construct($config);
        $this->addRequiredParam(QueryParameter::QUERY);
    }

    /**
     * @inheritdoc
     * @return JsonResponse
     */
    public function sendRequest()
    {
        $this->checkRequiredParamsAreSet();

        $responseContent = $this->findologicClient->request($this->buildRequestUrl());
        return new JsonResponse($responseContent, $this->findologicClient->getResponseTime());
    }

    /**
     * Sets the query param. It is used as the search query. When doing a suggestion request the min length for a
     * query is one character.
     *
     * @param $value string
     * @see https://docs.findologic.com/doku.php?id=smart_suggest_new#request
     * @return $this
     */
    public function setQuery($value)
    {
        $validator = new ParameterValidator([QueryParameter::QUERY => $value]);
        $validator->rule('string', QueryParameter::QUERY)
            ->rule('lengthMin', QueryParameter::QUERY, 1);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::QUERY);
        }

        $this->addParam(QueryParameter::QUERY, $value);
        return $this;
    }

    /**
     * This function exists for legacy reasons. Using this function will just call setQuery instead.
     *
     * @deprecated Use setQuery instead.
     * @param $value string
     * @see https://docs.findologic.com/doku.php?id=smart_suggest_new#request
     * @return $this
     */
    public function setAutoq($value)
    {
        return $this->setQuery($value);
    }

    /**
     * Adds the autocompleteblocks param. It allows overriding the blocks that are configured to be displayed in
     * the customer-login. As value use the BlockType class. For example BlockType::SUGGEST.
     *
     * @param $value string
     * @see https://docs.findologic.com/doku.php?id=smart_suggest_new#request
     * @return $this
     */
    public function addAutocompleteBlocks($value)
    {
        $validator = new ParameterValidator([QueryParameter::AUTOCOMPLETEBLOCKS => $value]);
        $validator->rule('string', QueryParameter::AUTOCOMPLETEBLOCKS);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::AUTOCOMPLETEBLOCKS);
        }

        $this->addParam(QueryParameter::AUTOCOMPLETEBLOCKS, $value, self::ADD_VALUE);
        return $this;
    }

    /**
     * Sets the usergrouphash param. It indicates which usergroup's products are used for generating suggestions. This
     * parameter is only relevant in case usergroup information is exported.
     *
     * @param $value string
     * @see https://docs.findologic.com/doku.php?id=smart_suggest_new#request
     * @return $this
     */
    public function setUsergrouphash($value)
    {
        $validator = new ParameterValidator([QueryParameter::GROUP => $value]);
        $validator->rule('string', QueryParameter::GROUP);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::GROUP);
        }

        $this->addParam(QueryParameter::GROUP, $value);
        return $this;
    }

    /**
     * Sets the multishop_id param. Required for PlentyMarkets shops. Has no effect for other shop systems.
     *
     * @param $value string
     * @see https://docs.findologic.com/doku.php?id=smart_suggest_new#request
     * @return $this
     */
    public function setMultishopId($value)
    {
        $validator = new ParameterValidator([QueryParameter::MULTISHOP_ID => $value]);
        $validator->rule('integer', QueryParameter::MULTISHOP_ID);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::MULTISHOP_ID);
        }

        $this->addParam(QueryParameter::MULTISHOP_ID, $value, self::ADD_VALUE);
        return $this;
    }
}
