<?php

namespace FINDOLOGIC\Api\Tests\RequestBuilders\Json;

use FINDOLOGIC\Api\RequestBuilders\Json\SuggestRequestBuilder;

trait SuggestionDataProvider
{
    public function setRequiredParamsForSuggestionRequestBuilder(SuggestRequestBuilder $suggestionRequestBuilder)
    {
        $suggestionRequestBuilder->setShopurl('www.blubbergurken.io');
    }

    public function queryProvider()
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

    public function invalidQueryProvider()
    {
        return [
            'integer as query' => [
                'query' => 21,
            ],
            'object as query' => [
                'query' => new \stdClass(),
            ],
            'float as query' => [
                'query' => 3.1415,
            ],
        ];
    }

    public function autocompleteBlocksProvider()
    {
        return [
            'normal autocompleteBlock' => ['suggest'],
            'other autocompleteBlock' => ['product'],
            'another autocompleteBlock' => ['promotion'],
            'more different autocompleteBlock' => ['ordernumber']
        ];
    }

    public function invalidAutocompleteBlocksProvider()
    {
        return [
            'some different string' => ['bom'],
            'almost some real autocompleteBlock' => ['ordernumber '],
            'an object as autocompleteBlock' => [new \stdClass()],
        ];
    }

    public function usergroupProvider()
    {
        return [
            'normal usergroup' => ['customer132'],
            'other usergroup' => ['1fd7fad0263a7d9a5705ad6f58ca5b70'],
            'another usergroup' => ['e665ef44bc0783813a79f564daf873d4c1f60b8e41e740e4e1154e3e5982729a'],
            'more different usergroup' => ['3466383163383731386539636430626436386366393836326463616661313337356364']
        ];
    }

    public function invalidUsergroupProvider()
    {
        return [
            'integer usergroup' => [1337],
            'float usergroup' => [13.37],
            'object as usergroup' => [new \stdClass()],
        ];
    }

    public function multishopIdProvider()
    {
        return [
            'normal usergroup' => [1],
            'other usergroup' => [2],
            'another usergroup' => [46],
            'more different usergroup' => [1337]
        ];
    }

    public function invalidMultishopIdProvider()
    {
        return [
            'string multishop_id' => ['1.1'],
            'float multishop_id' => [13.37],
            'object as multishop_id' => [new \stdClass()],
        ];
    }
}
