<?php

namespace FINDOLOGIC\Api\RequestBuilders\Json;

use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\Definitions\SuggestQueryParameter;
use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\RequestBuilders\RequestBuilder;
use FINDOLOGIC\Api\ResponseObjects\Json\SuggestResponse;
use FINDOLOGIC\Api\Validators\ParameterValidator;

class SuggestRequestBuilder extends RequestBuilder
{
    protected $endpoint = Endpoint::SUGGEST;

    public function __construct()
    {
        parent::__construct();
        $this->addRequiredParam(QueryParameter::QUERY);
    }

    /**
     * Sets the query param. It is used as the search query. When doing a suggest request the min length for a
     * query is one character.
     *
     * @param $value string
     * @see https://docs.findologic.com/doku.php?id=smart_suggest_new#request
     * @return $this
     */
    public function setQuery($value)
    {
        $validator = new ParameterValidator([QueryParameter::QUERY => $value]);
        $validator
            ->rule('string', QueryParameter::QUERY)
            ->rule('lengthMin', QueryParameter::QUERY, 1);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::QUERY);
        }

        $this->addParam(QueryParameter::QUERY, $value);
        return $this;
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
        $validator = new ParameterValidator([SuggestQueryParameter::AUTOCOMPLETEBLOCKS => $value]);
        $validator->rule('isAutocompleteBlockParam', SuggestQueryParameter::AUTOCOMPLETEBLOCKS);

        if (!$validator->validate()) {
            throw new InvalidParamException(SuggestQueryParameter::AUTOCOMPLETEBLOCKS);
        }

        $this->addParam(SuggestQueryParameter::AUTOCOMPLETEBLOCKS, $value, self::ADD_VALUE);
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
        $validator = new ParameterValidator([SuggestQueryParameter::USERGROUPHASH => $value]);
        $validator->rule('string', SuggestQueryParameter::USERGROUPHASH);

        if (!$validator->validate()) {
            throw new InvalidParamException(SuggestQueryParameter::USERGROUPHASH);
        }

        $this->addParam(SuggestQueryParameter::USERGROUPHASH, $value);
        return $this;
    }

    /**
     * Sets the multishop_id param. Required for PlentyMarkets shops. Has no effect for other shop systems.
     *
     * @param $value int
     * @see https://docs.findologic.com/doku.php?id=smart_suggest_new#request
     * @return $this
     */
    public function setMultishopId($value)
    {
        $validator = new ParameterValidator([SuggestQueryParameter::MULTISHOP_ID => $value]);
        $validator->rule('integer', SuggestQueryParameter::MULTISHOP_ID);

        if (!$validator->validate()) {
            throw new InvalidParamException(SuggestQueryParameter::MULTISHOP_ID);
        }

        $this->addParam(SuggestQueryParameter::MULTISHOP_ID, $value, self::ADD_VALUE);
        return $this;
    }
}
