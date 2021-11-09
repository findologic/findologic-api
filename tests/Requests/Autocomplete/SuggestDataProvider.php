<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests\Requests\Autocomplete;

use FINDOLOGIC\Api\Requests\Autocomplete\SuggestRequest;

trait SuggestDataProvider
{
    public function setRequiredParamsForSuggestRequest(SuggestRequest $suggestRequest): void
    {
        $suggestRequest->setShopUrl('www.blubbergurken.io');
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function queryProvider(): array
    {
        return [
            'some random query can be set' => [
                'expectedQuery' => 'something',
            ],
            'an empty query can be set' => [
                'expectedQuery' => '',
            ],
            'special characters in query should be set' => [
                'expectedQuery' => '/ /',
            ],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function invalidQueryProvider(): array
    {
        return [
            'empty query' => [
                'query' => '',
            ],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function autocompleteBlocksProvider(): array
    {
        return [
            'normal autocompleteBlock' => ['suggest'],
            'other autocompleteBlock' => ['product'],
            'another autocompleteBlock' => ['promotion'],
            'more different autocompleteBlock' => ['ordernumber']
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function invalidAutocompleteBlocksProvider(): array
    {
        return [
            'some different string' => ['bom'],
            'almost some real autocompleteBlock' => ['ordernumber '],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function usergroupProvider(): array
    {
        return [
            'normal usergroup' => ['customer132'],
            'other usergroup' => ['1fd7fad0263a7d9a5705ad6f58ca5b70'],
            'another usergroup' => ['e665ef44bc0783813a79f564daf873d4c1f60b8e41e740e4e1154e3e5982729a'],
            'more different usergroup' => ['3466383163383731386539636430626436386366393836326463616661313337356364']
        ];
    }

    /**
     * @return array<string, array<int, int>>
     */
    public function multishopIdProvider(): array
    {
        return [
            'normal usergroup' => [1],
            'other usergroup' => [2],
            'another usergroup' => [46],
            'more different usergroup' => [1337]
        ];
    }
}
