<?php

namespace FINDOLOGIC\Api\Responses\Json10;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use FINDOLOGIC\Api\Responses\Json10\Properties\Request;
use FINDOLOGIC\Api\Responses\Response;

class Json10Response extends Response
{
    /** @var Request */
    private $request;

    protected function buildResponseElementInstances($response)
    {
        $this->request = ResponseHelper::castTo($response, 'request', Request::class);
        // TODO: Implement buildResponseElementInstances() method.
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}