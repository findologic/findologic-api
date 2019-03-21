<?php

namespace FINDOLOGIC\Api\Tests\Definitions;

use FINDOLOGIC\Api\Definitions\SuggestQueryParameter;
use FINDOLOGIC\Api\Tests\TestBase;

class SuggestQueryParameterTest extends TestBase
{
    public function testAllSuggestQueryParametersAreAvailable()
    {
        $expectedAvailableSuggestQueryParameters = [
            'autocompleteblocks',
            'usergrouphash',
            'multishop_id',
        ];
        $availableSuggestQueryParameters = SuggestQueryParameter::getList();

        $this->assertEquals($expectedAvailableSuggestQueryParameters, $availableSuggestQueryParameters);
    }
}
