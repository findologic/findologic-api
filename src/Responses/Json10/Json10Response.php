<?php

namespace FINDOLOGIC\Api\Responses\Json10;

use FINDOLOGIC\Api\Responses\Json10\Properties\Request;
use FINDOLOGIC\Api\Responses\Json10\Properties\Result;
use FINDOLOGIC\Api\Responses\Response;

class Json10Response extends Response
{
    /** @var Request */
    private $request;

    /** @var Result */
    private $result;

    protected function buildResponseElementInstances($response)
    {
        $parsedResponse = json_decode($response, true);

        $this->request = new Request($parsedResponse['request']);
        $this->result = new Result($parsedResponse['result']);
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }
}
