<?php

namespace FINDOLOGIC\Api\Tests\Helpers;

use FINDOLOGIC\Api\Helpers\UserAgent;
use FINDOLOGIC\Api\Tests\TestBase;

class UserAgentTest extends TestBase
{
    public function tearDown()
    {
        @rename(
            __DIR__ . '/../../../vendor/composer/installed_.json',
            __DIR__ . '/../../../vendor/composer/installed.json'
        );
        @rename(
            __DIR__ . '/../../../composer_.json',
            __DIR__ . '/../../../composer.json'
        );
    }

    public function testFetchFromInstalledJson()
    {
        // TODO: Fake an installation.
        $this->assertNotContains('unknown', UserAgent::getUserAgent());
    }

    public function testFetchFromComposerJson()
    {
        $this->makeInstalledJsonUnreachable();
        $this->assertNotContains('unknown', UserAgent::getUserAgent());
    }

    public function testFallbackIfNoneExist()
    {
        $this->makeInstalledJsonUnreachable();
        $this->makeComposerJsonUnreachable();
        $this->assertContains('unknown', UserAgent::getUserAgent());
    }

    private function makeComposerJsonUnreachable()
    {
        rename(
            __DIR__ . '/../../../composer.json',
            __DIR__ . '/../../../composer_.json'
        );
    }

    private function makeInstalledJsonUnreachable()
    {
        rename(
            __DIR__ . '/../../../vendor/composer/installed.json',
            __DIR__ . '/../../../vendor/composer/installed_.json'
        );
    }
}
