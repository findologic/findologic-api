<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests\Requests\Builder;

use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Definitions\RangeSlider;
use FINDOLOGIC\Api\Requests\Builder\RequestBuilder;
use FINDOLOGIC\Api\Requests\Builder\SearchRequestBuilder;
use PHPUnit\Framework\TestCase;

class SearchRequestBuilderTest extends TestCase
{
    private function getDefaultConfig(): Config
    {
        return new Config('ABCDABCDABCDABCDABCDABCDABCDABCD');
    }

    public function testAttributesAreProperlyAdded(): void
    {
        $expectedQueryParams = [
            'attrib' => [
                'blub' => ['yay'],
                'noice' => ['yess'],
                'price' => [
                    'min' => '10',
                    'max' => '100',
                ],
                'weight' => [
                    'min' => '1',
                    'max' => '100',
                ]
            ]
        ];

        /** @var SearchRequestBuilder $searchRequestBuilder */
        $searchRequestBuilder = RequestBuilder::getInstance(RequestBuilder::TYPE_SEARCH_REQUEST);
        $searchRequestBuilder->addAttribute('blub', 'yay');
        $searchRequestBuilder->addAttribute('noice', 'yess');
        $searchRequestBuilder->addAttribute('price', 10, ['specifier' => RangeSlider::SPECIFIER_MIN]);
        $searchRequestBuilder->addAttribute('price', 100, ['specifier' => RangeSlider::SPECIFIER_MAX]);
        $searchRequestBuilder->addRangeAttribute('weight', 1, 100);

        $config = $this->getDefaultConfig();
        $request = $searchRequestBuilder->buildRequest($config);

        parse_str($request->getUri()->getQuery(), $queryParams);

        $this->assertSame($expectedQueryParams, $queryParams);
    }
}
