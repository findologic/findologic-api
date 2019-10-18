# FINDOLOGIC API

> Version v1.0.0

[![Build Status](https://travis-ci.org/findologic/findologic-api.svg?branch=master)](https://travis-ci.org/findologic/findologic-api)
[![Maintainability](https://api.codeclimate.com/v1/badges/b7efba0a8475fc2095cc/maintainability)](https://codeclimate.com/github/findologic/findologic-api/maintainability)
[![codecov](https://codecov.io/gh/findologic/findologic-api/branch/master/graph/badge.svg)](https://codecov.io/gh/findologic/findologic-api)
[![Packagist](https://img.shields.io/packagist/v/findologic/findologic-api.svg)](https://packagist.org/packages/findologic/findologic-api)

## Synopsis

This library not only helps requesting the FINDOLOGIC API but also getting data from the response and mapping them to corresponding objects.
You won't have to mess around with sending requests and getting the data from the FINDOLOGICs response anymore.  

You want to get filters? Just call `->getFilters()` on your response object. It really is that simple and if you dont trust us,
try out the [Basic Usage](#basic-usage) or test the [Examples](#examples).

To have a better understanding about the API, please make sure to read the general FINDOLOGIC API documentation. We already got you covered with quicklinks to it:

 * [Requesting the API](https://docs.findologic.com/doku.php?id=integration_documentation:request)
 * [XML response](https://docs.findologic.com/doku.php?id=integration_documentation:response_xml)

## Installation

For a simple installation you can use [Composer](https://getcomposer.org/).
Using this command will install the latest version.

```bash
composer require findologic/findologic-api
```

## Basic usage

The usage is pretty simple. Here is an example:

```php
// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';

use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Client;
use FINDOLOGIC\Api\Requests\SearchNavigation\SearchRequest;
use FINDOLOGIC\Api\Responses\Xml21\Xml21Response

$config = new Config();
// ServiceId/Shopkey, you can find it in the customer account.
$config->setServiceId('ABCDABCDABCDABCDABCDABCDABCDABCD');

// Client used for requests
$client = new Client($config);

$searchRequest = new SearchRequest();
$searchRequest
    ->setQuery('shirt') // Users search query
    ->setShopUrl('blubbergurken.de') // Url of the shop
    ->setUserIp('127.0.0.1') // Users IP
    ->setReferer($_SERVER['HTTP_REFERER']) // Page where search was fired
    ->setRevision('1.0.0'); // Version of your API wrapper

/** @var Xml21Response $xmlResponse */
$xmlResponse = $client->send($searchRequest);

var_dump($xmlResponse->getFilters()); // Get all filters easily
var_dump($xmlResponse); // Entire response, full of helper methods
```

## Examples

Method calls, return values and examples can be found in our [Project Wiki](https://github.com/findologic/findologic-api/wiki).

## Requirements

 * [PHP 5.6+](https://php.net/)
 * [Composer](https://getcomposer.org/)
 
#### PHP packages
 * [PHP cURL extension](https://www.php.net/manual/en/curl.installation.php)
 * [PHP SimpleXML](https://www.php.net/manual/en/simplexml.installation.php)
 * [PHP JSON](https://www.php.net/manual/en/json.installation.php)
 * [PHP DOM](https://www.php.net/manual/en/dom.installation.php)
 * [PHP libxml](https://www.php.net/manual/en/libxml.installation.php)


## Found a bug?

We need your help! If you find any bug, please submit an issue and use our template! Be as precise as possible
so we can reproduce your case easier. For further information, please refer to our issue template at
[.github/ISSUE_TEMPLATE/bug_report.md](.github/ISSUE_TEMPLATE/bug_report.md).

## Contributing

Please check [our contribution guide](contributing.md) on how to contribute.
