<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests\Definitions;

use FINDOLOGIC\Api\Definitions\OutputAdapter;
use FINDOLOGIC\Api\Tests\TestBase;

class OutputAdapterTest extends TestBase
{
    public function testAllQueryParametersAreAvailable()
    {
        $expectedAvailableOutputAdapter = [
            'XML_21' => 'XML_2.1',
            'HTML_20' => 'HTML_2.0',
            'HTML_30' => 'HTML_3.0',
            'HTML_31' => 'HTML_3.1',
            'JSON_10' => 'JSON_1.0'
        ];
        $availableOutputAdapter = OutputAdapter::getConstants();

        $this->assertEquals($expectedAvailableOutputAdapter, $availableOutputAdapter);
    }
}
