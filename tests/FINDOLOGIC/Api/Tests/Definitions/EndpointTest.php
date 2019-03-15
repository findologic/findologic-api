<?php

namespace FINDOLOGIC\Api\Tests\Definitions;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Tests\TestBase;

class EndpointTest extends TestBase
{
    public function testAllEndpointsAreAvailable()
    {
        $expectedAvailableEndpoints = [
            'alivetest.php',
            'index.php',
            'selector.php',
            'autocomplete.php',
        ];
        $availableEndpoints = Endpoint::getAvailableEndpoints();

        $this->assertEquals($expectedAvailableEndpoints, $availableEndpoints);
    }
}
