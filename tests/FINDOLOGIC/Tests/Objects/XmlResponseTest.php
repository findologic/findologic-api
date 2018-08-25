<?php

namespace FINDOLOGIC\Tests\Objects;

use FINDOLOGIC\Objects\XmlResponse;
use PHPUnit\Framework\TestCase;

class XmlResponseTest extends TestCase
{
    public function testResponse()
    {
        $now = microtime();
        // Get contents from a real response locally.
        $realResponseData = file_get_contents(__DIR__ . '/../../Mockdata/demoResponse.xml');
        $response = new XmlResponse($realResponseData);
        var_dump($response->getProducts());
        echo(microtime() - $now);
    }
}
