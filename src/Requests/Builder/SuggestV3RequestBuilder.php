<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\Builder;

use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

class SuggestV3RequestBuilder extends RequestBuilder
{
    private array $params = [
        'type' => 'result_v3'
    ];

    protected function getEndpoint(): string
    {
        return Endpoint::SUGGEST;
    }

    public function reset(): void
    {
        $this->params = ['type' => 'result_v3'];
    }

    public function setQuery(string $query): self
    {
        $this->params[QueryParameter::QUERY] = $query;

        return $this;
    }

    public function setCount(int $count): self
    {
        $this->params[QueryParameter::COUNT] = $count;

        return $this;
    }

    public function setUserGroupHash(string $hash): self
    {
        $this->params[QueryParameter::USERGROUP] = $hash;

        return $this;
    }

    public function setGroup(string $group): self
    {
        $this->params[QueryParameter::GROUP] = $group;

        return $this;
    }

    public function setPushAttrib(string $name, $value, float $boostFactor): self
    {
        if (!is_string($value) && !is_numeric($value)) {
            throw new InvalidParamException(QueryParameter::PUSH_ATTRIB);
        }

        if (!isset($this->params[QueryParameter::PUSH_ATTRIB])) {
            $this->params[QueryParameter::PUSH_ATTRIB] = [];
        }

        if (!isset($this->params[QueryParameter::PUSH_ATTRIB][$name])) {
            $this->params[QueryParameter::PUSH_ATTRIB][$name] = [];
        }

        $this->params[QueryParameter::PUSH_ATTRIB][$name][$value] = $boostFactor;

        return $this;
    }

    public function setRevision(string $revision): self
    {
        $this->params[QueryParameter::REVISION] = $revision;

        return $this;
    }

    public function setProperties(array $properties): self
    {
        $this->params[QueryParameter::PROPERTIES] = $properties;

        return $this;
    }

    public function setAutocompleteBlocks(array $autocompleteBlocks): self
    {
        $this->params[QueryParameter::AUTOCOMPLETE_BLOCKS] = $autocompleteBlocks;

        return $this;
    }

    public function setRequestId(string $requestId): self
    {
        $this->params[QueryParameter::REQUEST_ID] = $requestId;

        return $this;
    }

    public function buildRequest(Config $config): RequestInterface
    {
        $queryParams = http_build_query($this->params);
        $apiUrl = $config->getFullApiUrl() . $this->getEndpoint();

        return new Request('GET', sprintf('%s?%s', $apiUrl, $queryParams));
    }
}
