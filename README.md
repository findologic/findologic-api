# FINDOLOGIC API

> Version v1.0.0-beta.1

[![Travis](https://travis-ci.org/TheKeymaster/findologic-api.svg?branch=master)](https://travis-ci.org/TheKeymaster/findologic-api)
[![Maintainability](https://api.codeclimate.com/v1/badges/d604675c46586292c20f/maintainability)](https://codeclimate.com/github/TheKeymaster/findologic-api/maintainability)
[![codecov](https://codecov.io/gh/TheKeymaster/findologic-api/branch/master/graph/badge.svg)](https://codecov.io/gh/TheKeymaster/findologic-api)

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
composer require thekeymaster/findologic-api
```

## Basic usage

The usage is pretty simple. Here is an example:

```php
use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\RequestBuilders\Xml\SearchRequestBuilder;
use FINDOLOGIC\Api\Client;

// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';

$config = new Config();
$config->setServiceId('ABCDABCDABCDABCDABCDABCDABCDABCD');

$searchRequest = new SearchRequestBuilder();
$searchRequest
    ->setQuery('shirt') // Users search query
    ->setShopurl('blubbergurken.de') // Url of the shop
    ->setUserip('127.0.0.1') // Users IP
    ->setReferer('https://shop.url/AGB') // Page where search was fired
    ->setRevision('1.0.0'); // Version of your API wrapper

$client = new Client($config); // Client used for requests

/** @var XmlResponse $xmlResponse */
$xmlResponse = $client->send($searchRequest);

var_dump($xmlResponse->getFilters()); // Get all filters easily
var_dump($xmlResponse); // Entire response, full of helper methods
```

## Examples

Method calls, return values and examples can be found in our [Project Wiki](https://github.com/TheKeymaster/findologic-api/wiki).

## Requirements

 * [PHP 5.6 or higher](https://php.net/) older versions are not supported.
 * [Composer](https://getcomposer.org/)

## Found a bug?

We need your help! If you find any bug, please submit an issue and use our template! Be as precise as possible
so we can reproduce your case easier. For further information, please refer to our issue template at
`.github/ISSUE_TEMPLATE/bug_report.md`.
