<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Json10;

use FINDOLOGIC\Api\Responses\Json10\Properties\Request;
use FINDOLOGIC\Api\Responses\Json10\Properties\Result;
use FINDOLOGIC\Api\Responses\Response;

class Json10Response extends Response
{
    private Request $request;
    private Result $result;

    protected function buildResponseElementInstances(string $response): void
    {
        $parsedResponse = json_decode($response, true);

        $this->request = new Request($parsedResponse['request']);
        $this->result = new Result($parsedResponse['result']);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResult(): Result
    {
        return $this->result;
    }
}
