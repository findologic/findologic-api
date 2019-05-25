<?php

namespace FINDOLOGIC\Api\Tests\Definitions;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Tests\TestBase;

class EndpointTest extends TestBase
{
    public function testAllEndpointsAreAvailable()
    {
        $expectedAvailableEndpoints = [
            'ALIVETEST' => 'alivetest.php',
            'SEARCH' => 'index.php',
            'NAVIGATION' => 'selector.php',
            'SUGGEST' => 'autocomplete.php',
            'TRACKING' => 'tracking.php'
        ];
        $availableEndpoints = Endpoint::getConstants();

        $this->assertEquals($expectedAvailableEndpoints, $availableEndpoints);
    }
}
