<?php

namespace FINDOLOGIC\Request\Tests;


use FINDOLOGIC\Request\Requester;
use PHPUnit\Framework\TestCase;

class RequesterTest extends TestCase
{
    public function setUp()
    {
        // TODO: Mock away HTTP requests.
    }

    public function testRequestIsSent()
    {
        $requester = Requester::create(Requester::TYPE_SEARCH);
        $requester->addShopkey('80AB18D4BE2654A78244106AD315DC2C');
        $requester->addReferer('https://converschig24.com/');
        $requester->addRevision('1.33.7');
        $requester->addShopurl('www.blubbergurken24.io');
        $requester->addUserip('127.0.0.1');

        // TODO: Allow passing a HTTP client with mocked-away HTTP requests to the requester.
        $requester->send();
    }
}