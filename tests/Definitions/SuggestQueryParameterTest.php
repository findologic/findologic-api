<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests\Definitions;

use FINDOLOGIC\Api\Definitions\SuggestQueryParameter;
use FINDOLOGIC\Api\Tests\TestBase;

class SuggestQueryParameterTest extends TestBase
{
    public function testAllSuggestQueryParametersAreAvailable(): void
    {
        $expectedAvailableSuggestQueryParameters = [
            'AUTOCOMPLETEBLOCKS' => 'autocompleteblocks',
            'USERGROUPHASH' => 'usergrouphash',
            'MULTISHOP_ID' => 'multishop_id'
        ];
        $availableSuggestQueryParameters = SuggestQueryParameter::getConstants();

        $this->assertEquals($expectedAvailableSuggestQueryParameters, $availableSuggestQueryParameters);
    }
}
