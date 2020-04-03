<?php

namespace FINDOLOGIC\Api\Tests\Definitions;

use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\Tests\TestBase;

class QueryParameterTest extends TestBase
{
    public function testAllQueryParametersAreAvailable()
    {
        $expectedAvailableQueryParameters = [
            'SERVICE_ID' => 'shopkey',
            'SHOP_URL' => 'shopurl',
            'USER_IP' => 'userip',
            'REFERER' => 'referer',
            'REVISION' => 'revision',
            'QUERY' => 'query',
            'ATTRIB' => 'attrib',
            'ORDER' => 'order',
            'PROPERTIES' => 'properties',
            'PUSH_ATTRIB' => 'pushAttrib',
            'COUNT' => 'count',
            'FIRST' => 'first',
            'IDENTIFIER' => 'identifier',
            'GROUP' => 'group',
            'USERGROUP' => 'usergrouphash',
            'FORCE_ORIGINAL_QUERY' => 'forceOriginalQuery',
            'OUTPUT_ATTRIB' => 'outputAttrib',
            'SELECTED' => 'selected',
            'OUTPUT_ADAPTER' => 'outputAdapter'
        ];
        $availableQueryParameters = QueryParameter::getConstants();

        $this->assertEquals($expectedAvailableQueryParameters, $availableQueryParameters);
    }
}
