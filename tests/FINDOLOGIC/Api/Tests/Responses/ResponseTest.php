<?php

namespace FINDOLOGIC\Api\Tests\Responses;

use FINDOLOGIC\Api\Requests\SearchNavigation\SearchRequest;
use FINDOLOGIC\Api\Responses\Response;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class ResponseTest extends TestCase
{
    public function testUnknownResponseWillThrowAnException()
    {
        $expectedOutputAdapter = 'HTML_4.20';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Unknown or invalid outputAdapter "%s"', $expectedOutputAdapter));

        /** @var SearchRequest|PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->getMockBuilder(SearchRequest::class)
            ->disableOriginalConstructor()
            ->setMethods(['getOutputAdapter'])
            ->getMock();
        $request->expects($this->any())->method('getOutputAdapter')->willReturn($expectedOutputAdapter);

        Response::buildInstance($request, new GuzzleResponse, null, null);
    }
}
