<?php

namespace FINDOLOGIC\Request\Tests;


use FINDOLOGIC\Request\Request;
use PHPUnit\Framework\TestCase;

class RequesterTest extends TestCase
{
    public function setUp()
    {
        // TODO: Mock away HTTP requests.
    }

    public function testRequestIsSent()
    {
        $requester = Request::create(Request::TYPE_SEARCH);
        $requester->setShopkey('80AB18D4BE2654A78244106AD315DC2C');
        $requester->setReferer('https://converschig24.com/');
        $requester->setRevision('1.33.7');
        $requester->setShopurl('www.blubbergurken24.io');
        $requester->setUserip('127.0.0.1');

        // TODO: Allow passing a HTTP client with mocked-away HTTP requests to the requester.
        $requester->send();
    }
}