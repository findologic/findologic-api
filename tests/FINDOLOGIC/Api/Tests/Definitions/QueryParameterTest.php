<?php

namespace FINDOLOGIC\Api\Tests\Definitions;

use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\Tests\TestBase;

class QueryParameterTest extends TestBase
{
    public function testAllQueryParametersAreAvailable()
    {
        $expectedAvailableQueryParameters = [
            'shopkey',
            'shopurl',
            'userip',
            'referer',
            'revision',
            'query',
            'attrib',
            'order',
            'properties',
            'pushAttrib',
            'count',
            'first',
            'identifier',
            'group',
            'forceOriginalQuery',
            'outputAttrib',
            'selected'
        ];
        $availableQueryParameters = QueryParameter::getList();

        $this->assertEquals($expectedAvailableQueryParameters, $availableQueryParameters);
    }
}
