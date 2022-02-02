<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\Builder;

use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\Definitions\RangeSlider;
use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

abstract class ResultRequestBuilderBase extends RequestBuilder
{
    private array $params = [];

    public function reset(): void
    {
        $this->params = [];
    }

    /**
     * Adds a single attribute value to all selected attributes.
     * Examples:
     * ```
     * $this->addAttribute('cat', 'Men_Shoes');
     * $this->addAttribute('Color', 'Blue');
     * $this->addAttribute('Size', 'XL');
     * $this->addAttribute('vendor', 'Findologic');
     * $this->addAttribute(
     *     'price',
     *     30.55,
     *     ['specifier' => \FINDOLOGIC\Api\Definitions\RangeSlider::SPECIFIER_MIN]
     * );
     * $this->addAttribute(
     *     'price',
     *     100,
     *     ['specifier' => \FINDOLOGIC\Api\Definitions\RangeSlider::SPECIFIER_MAX]
     * );
     * ```
     *
     * @param string|int|float $value
     * @param array{specifier: string} $options
     */
    public function addAttribute(string $name, $value, array $options = []): ResultRequestBuilderBase
    {
        if (!is_string($value) && !is_numeric($value)) {
            throw new InvalidParamException(QueryParameter::ATTRIB);
        }

        if (!isset($this->params[QueryParameter::ATTRIB])) {
            $this->params[QueryParameter::ATTRIB] = [];
        }

        if (!isset($this->params[QueryParameter::ATTRIB][$name])) {
            $this->params[QueryParameter::ATTRIB][$name] = [];
        }

        if (isset($options['specifier'])) {
            $this->params[QueryParameter::ATTRIB][$name][$options['specifier']] = $value;
            return $this;
        }

        $this->params[QueryParameter::ATTRIB][$name][] = $value;

        return $this;
    }

    /**
     * Simplified version for adding attribute ranges.
     * ```
     * $this->addRangeAttribute('price', 20.0, 100.0);
     * $this->addRangeAttribute('weight', 100);
     * $this->addRangeAttribute('price', null, 500.33);
     * ```
     *
     * @return $this
     */
    public function addRangeAttribute(string $name, ?float $min = null, ?float $max = null): self
    {
        if ($min !== null) {
            $this->addAttribute($name, $min, ['specifier' => RangeSlider::SPECIFIER_MIN]);
        }

        if ($max !== null) {
            $this->addAttribute($name, $max, ['specifier' => RangeSlider::SPECIFIER_MAX]);
        }

        return $this;
    }

    public function setFirst(int $first): ResultRequestBuilderBase
    {
        $this->setParam(QueryParameter::FIRST, $first);

        return $this;
    }

    public function setCount(int $count): ResultRequestBuilderBase
    {
        $this->setParam(QueryParameter::COUNT, $count);

        return $this;
    }

    public function setOrder(string $order): ResultRequestBuilderBase
    {
        $this->setParam(QueryParameter::ORDER, $order);

        return $this;
    }

    public function setUserGroupHash(string $hash): ResultRequestBuilderBase
    {
        $this->setParam(QueryParameter::USERGROUP, $hash);

        return $this;
    }

    public function setGroup(string $group): ResultRequestBuilderBase
    {
        $this->setParam(QueryParameter::GROUP, $group);

        return $this;
    }

    public function setUserIp(string $ip): ResultRequestBuilderBase
    {
        $this->setParam(QueryParameter::USER_IP, $ip);

        return $this;
    }

    public function setPushAttrib(string $name, $value, float $boostFactor): ResultRequestBuilderBase
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

    public function setOutputAttrib(string $outputAttrib): ResultRequestBuilderBase
    {
        $this->setParam(QueryParameter::OUTPUT_ATTRIB, $outputAttrib);

        return $this;
    }

    public function setProperties(array $properties): ResultRequestBuilderBase
    {
        $this->setParam(QueryParameter::PROPERTIES, $properties);

        return $this;
    }

    public function setOutputAdapter(string $outputAdapter): ResultRequestBuilderBase
    {
        $this->setParam(QueryParameter::OUTPUT_ADAPTER, $outputAdapter);

        return $this;
    }

    public function setUserId(string $userId): ResultRequestBuilderBase
    {
        $this->setParam(QueryParameter::USER_ID, $userId);

        return $this;
    }

    public function setRequestId(string $requestId): ResultRequestBuilderBase
    {
        $this->setParam(QueryParameter::REQUEST_ID, $requestId);

        return $this;
    }

    public function setUserAgent(string $userAgent): ResultRequestBuilderBase
    {
        $this->setParam(QueryParameter::USER_AGENT, $userAgent);

        return $this;
    }

    public function setRevision(string $revision): ResultRequestBuilderBase
    {
        $this->setParam(QueryParameter::REVISION, $revision);

        return $this;
    }

    public function setCustomParam(string $name, $value): ResultRequestBuilderBase
    {
        $this->setParam($name, $value);

        return $this;
    }

    public function buildRequest(Config $config): RequestInterface
    {
        $queryParams = http_build_query($this->params);
        $apiUrl = $config->getFullApiUrl() . $this->getEndpoint();

        return new Request('GET', sprintf('%s?%s', $apiUrl, $queryParams));
    }

    protected function setParam(string $name, $value): ResultRequestBuilderBase
    {
        $this->params[$name] = $value;

        return $this;
    }
}
