<?php

namespace FINDOLOGIC\Api\Tests\Responses\Json10;

use FINDOLOGIC\Api\Responses\Json10\Json10Response;
use PHPUnit\Framework\TestCase;

class Json10ResponseTest extends TestCase
{
    /**
     * Will use a real response that could come from a request. It returns the Object.
     *
     * @param string $filename
     *
     * @return Json10Response
     */
    public function getRealResponseData($filename = 'demoResponse.json')
    {
        // Get contents from a real response locally.
        $realResponseData = file_get_contents(__DIR__ . '/../../Mockdata/Json10/' . $filename);

        return new Json10Response($realResponseData);
    }

    public function testRequestWillBeReturnedAsExpected()
    {
        $response = $this->getRealResponseData();
        $request = $response->getRequest();

        $this->assertSame(0, $request->getFirst());
        $this->assertSame(24, $request->getCount());
        $this->assertSame('ABCD1234ABCD1234ABCD1234ABCD1234', $request->getServiceId());
        $this->assertNull($request->getUsergroup());

        $order = $request->getOrder();

        $this->assertSame('salesfrequency', $order->getField());
        $this->assertTrue($order->isRelevanceBased());
        $this->assertSame('DESC', $order->getDirection());
    }
}
