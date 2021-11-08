<?php

namespace FINDOLOGIC\Api\Tests\Responses;

use FINDOLOGIC\Api\Requests\SearchNavigation\SearchRequest;
use FINDOLOGIC\Api\Responses\Autocomplete\SuggestResponse;
use FINDOLOGIC\Api\Responses\Html\GenericHtmlResponse;
use FINDOLOGIC\Api\Responses\Item\ItemUpdateResponse;
use FINDOLOGIC\Api\Responses\Json10\Json10Response;
use FINDOLOGIC\Api\Responses\Response;
use FINDOLOGIC\Api\Responses\Xml21\Xml21Response;
use FINDOLOGIC\Api\Tests\TestBase;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
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

        Response::buildInstance($request, new GuzzleResponse, null, null);
    }

    public function availableResponseProvider()
    {
        $expectedSuggestResponse = $this->getMockResponse('Autocomplete/demoResponseSuggest.json');
        $expectedHtmlResponse = $this->getMockResponse('Html/demoResponse.html');
        $expectedXml21Response = $this->getMockResponse('Xml21/demoResponse.xml');
        $expectedJson10Response = $this->getMockResponse('Json10/demoResponse.json');
        $expectedItemUpdateResponse = $this->getMockResponse('Item/demoResponse.json');

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
            'JSON 1.0 response' => [
                'response' => new Json10Response($expectedJson10Response),
                'expectedRawResponse' => $expectedJson10Response
            ],
            'ItemUpdate response' => [
                'response' => new ItemUpdateResponse($expectedItemUpdateResponse),
                'expectedRawResponse' => $expectedItemUpdateResponse
            ],
        ];
    }

    /**
     * @dataProvider availableResponseProvider
     * @param Response $response
     * @param string $expectedRawResponse
     */
    public function testGettingRawResponseAsStringReturnsItAsExpected(Response $response, $expectedRawResponse)
    {
        $this->assertEquals($expectedRawResponse, $response->getRawResponse());
    }
}
