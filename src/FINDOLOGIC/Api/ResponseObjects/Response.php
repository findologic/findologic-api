<?php

namespace FINDOLOGIC\Api\ResponseObjects;

abstract class Response
{
    /** @var float|null */
    protected $responseTime;

    /**
     * @param string $response Raw response as string.
     * @param null $responseTime Response time in microseconds.
     */
    abstract public function __construct($response, $responseTime = null);

    /**
     * Gets the response time that FINDOLOGIC took to respond to the request in microseconds. Please note that this
     * time also includes latency, etc.
     *
     * @return float|null
     */
    public function getResponseTime()
    {
        return $this->responseTime;
    }
}
