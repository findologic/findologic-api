<?php

namespace FINDOLOGIC\Api\Helpers;

class UserAgent
{
    const COMPOSER_INSTALL_DIR = __DIR__ . '/../../../../../vendor/composer/';
    const UNKNOWN_VERSION = 'unknown';

    /**
     * @return string
     */
    public static function getUserAgent()
    {
        return sprintf('FINDOLOGIC-API - Version "%s"', static::fetchVersion());
    }

    /**
     * @return string
     */
    private static function fetchVersion()
    {
        $installVersion = static::fetchVersionFromInstallJson();
        if ($installVersion) {
            return $installVersion;
        }

        $composerVersion = static::fetchVersionFromComposerJson();
        if ($composerVersion) {
            return $composerVersion;
        }

        return static::UNKNOWN_VERSION;
    }

    /**
     * @return string|null
     */
    private static function fetchVersionFromInstallJson()
    {
        // Suppress warnings, because this file could legitimate not exist.
        $rawContents = @file_get_contents(static::COMPOSER_INSTALL_DIR . 'installed.json');
        if (!$rawContents) {
            return null;
        }

        $contents = json_decode($rawContents, true);
        $key = array_search('findologic/findologic-api', $contents, true);

        return $key ? $contents[$key]['version'] : null;
    }

    /**
     * @return string|null
     */
    private static function fetchVersionFromComposerJson()
    {
        // Suppress warnings, because this file could legitimate not exist.
        $rawContents = @file_get_contents(__DIR__ . '/../../composer.json');
        $contents = json_decode($rawContents, true);

        return isset($contents['version']) ? $contents['version'] : null;
    }
}
