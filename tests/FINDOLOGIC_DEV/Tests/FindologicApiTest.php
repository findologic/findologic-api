<?php

namespace FINDOLOGIC_DEV\Tests;

use FINDOLOGIC_DEV\FindologicApi;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class FindologicApiTest extends TestCase
{
    public function testAlivetestWorks()
    {
        $httpClientMock = $this->createMock(Client::class);
        $findologicApi = new FindologicApi([
            FindologicApi::SHOPKEY => '80AB18D4BE2654A78244106AD315DC2C',
            FindologicApi::HTTP_CLIENT => $httpClientMock
        ]);

        $findologicApi
            ->setShopurl('www.blubbergurken.io')
            ->setUserip('127.0.0.1')
            ->setReferer('www.blubbergurken.io/blubbergurken-sale')
            ->setRevision('1.0.0');

        $findologicApi->sendSearchRequest();
    }
}