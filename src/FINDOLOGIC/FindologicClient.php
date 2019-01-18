<?php

namespace FINDOLOGIC;

/**
 * This class is responsible to send the requests.
 *
 * Class FindologicClient
 * @package FINDOLOGIC
 */
class FindologicClient
{
    /** @var FindologicConfig */
    private $config;

    public function __construct(FindologicConfig $config)
    {
        $this->config = $config;
    }

    public function request($url, $sendAlivetest = true)
    {
        // TODO: Send the requests and return the actual data.
    }
}
