<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\Builder;

use FINDOLOGIC\Api\Config;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;

abstract class RequestBuilder
{
    /**
     * @see SearchRequestBuilder
     */
    public const TYPE_SEARCH_REQUEST = 0;

    /**
     * @see NavigationRequestBuilder
     */
    public const TYPE_NAVIGATION_REQUEST = 1;

    /**
     * @see SuggestV3RequestBuilder
     */
    public const TYPE_SUGGEST_V3_REQUEST = 2;

    /**
     * @see AliveTestRequestBuilder
     */
    public const TYPE_ALIVETEST_REQUEST = 3;

    /**
     * @see ItemUpdateRequestBuilder
     */
    public const TYPE_ITEM_UPDATE_REQUEST = 4;

    public static function getInstance(int $type): RequestBuilder
    {
        switch ($type) {
            case self::TYPE_SEARCH_REQUEST:
                return new SearchRequestBuilder();
            case self::TYPE_NAVIGATION_REQUEST:
                return new NavigationRequestBuilder();
            case self::TYPE_SUGGEST_V3_REQUEST:
                return new SuggestV3RequestBuilder();
            case self::TYPE_ALIVETEST_REQUEST:
                return new AliveTestRequestBuilder();
            case self::TYPE_ITEM_UPDATE_REQUEST:
                return new ItemUpdateRequestBuilder();
            default:
                throw new InvalidArgumentException(sprintf('Unknown request builder type "%d"', $type));
        }
    }

    abstract public function buildRequest(Config $config): RequestInterface;
    abstract public function reset(): void;

    abstract protected function getEndpoint(): string;
}
