<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\Autocomplete;

use BadMethodCallException;
use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\Definitions\RequestMethod;
use FINDOLOGIC\Api\Definitions\SuggestQueryParameter;
use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\Requests\Request;
use FINDOLOGIC\Api\Validators\ParameterValidator;

class SuggestRequest extends Request
{
    protected string $endpoint = Endpoint::SUGGEST;
    protected string $method = RequestMethod::GET;

    public function __construct(array $params = [])
    {
        parent::__construct($params);
        $this->addRequiredParam(QueryParameter::QUERY);
    }

    public function getBody(): ?string
    {
        throw new BadMethodCallException('Request body is not supported for suggest requests');
    }

    /**
     * Sets the query param. It is used as the search query. When doing a suggest request the min length for a
     * query is one character.
     *
     * @see https://service.findologic.com/ps/centralized-frontend/spec/
     */
    public function setQuery(string $value): self
    {
        $validator = new ParameterValidator([QueryParameter::QUERY => $value]);
        $validator->rule('required', QueryParameter::QUERY);

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
     * @see https://service.findologic.com/ps/centralized-frontend/spec/
     */
    public function addAutocompleteBlocks(string $value): self
    {
        $validator = new ParameterValidator([SuggestQueryParameter::AUTOCOMPLETEBLOCKS => $value]);
        $validator->rule('isAutocompleteBlockParam', SuggestQueryParameter::AUTOCOMPLETEBLOCKS);

        if (!$validator->validate()) {
            throw new InvalidParamException(SuggestQueryParameter::AUTOCOMPLETEBLOCKS);
        }

        $this->addParam(SuggestQueryParameter::AUTOCOMPLETEBLOCKS, [$value], self::ADD_VALUE);

        return $this;
    }

    /**
     * Sets the usergrouphash param. It indicates which usergroup's products are used for generating suggestions. This
     * parameter is only relevant in case usergroup information is exported.
     *
     * @see https://service.findologic.com/ps/centralized-frontend/spec/
     */
    public function setUsergrouphash(string $value): self
    {
        $this->addParam(SuggestQueryParameter::USERGROUPHASH, $value);

        return $this;
    }

    /**
     * Sets the multishop_id param. Required for PlentyMarkets shops. Has no effect for other shop systems.
     *
     * @see https://service.findologic.com/ps/centralized-frontend/spec/
     */
    public function setMultishopId(int $value): self
    {
        $this->addParam(SuggestQueryParameter::MULTISHOP_ID, $value, self::ADD_VALUE);

        return $this;
    }
}
