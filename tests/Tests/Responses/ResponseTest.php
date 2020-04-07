<?php

namespace FINDOLOGIC\Api\Tests\Responses;

use FINDOLOGIC\Api\Requests\SearchNavigation\SearchRequest;
use FINDOLOGIC\Api\Responses\Autocomplete\SuggestResponse;
use FINDOLOGIC\Api\Responses\Html\GenericHtmlResponse;
use FINDOLOGIC\Api\Responses\Response;
use FINDOLOGIC\Api\Responses\Xml21\Xml21Response;
use FINDOLOGIC\Api\Tests\TestBase;
use InvalidArgumentException;
use PHPUnit_Framework_MockObject_MockObject;

class ResponseTest extends TestBase
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

        $this->setExpectationsForRequests('', '');
        Response::buildInstance($request, $this->responseMock, null, null);
    }

    public function availableResponseProvider()
    {
        $expectedSuggestResponse = $this->getMockResponse('Autocomplete/demoResponseSuggest.json');
        $expectedHtmlResponse = $this->getMockResponse('Html/demoResponse.html');
        $expectedXml21Response = $this->getMockResponse('Xml21/demoResponse.xml');

        return [
            'suggest response' => [
                'response' => new SuggestResponse($expectedSuggestResponse),
                'expectedRawResponse' => $expectedSuggestResponse
            ],
            'HTML response' => [
                'response' => new GenericHtmlResponse($expectedHtmlResponse),
                'expectedRawResponse' => $expectedHtmlResponse
            ],
            'XML 2.1 response' => [
                'response' => new Xml21Response($expectedXml21Response),
                'expectedRawResponse' => $expectedXml21Response
            ],
        ];
    }

    /**
     * @dataProvider availableResponseProvider
     *
     * @param Response $response
     * @param string $expectedRawResponse
     */
    public function testGettingRawResponseAsStringReturnsItAsExpected(Response $response, $expectedRawResponse)
    {
        $this->assertEquals($expectedRawResponse, $response->getRawResponse());
    }
}
