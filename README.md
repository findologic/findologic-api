# FINDOLOGIC-API

[![Build Status](https://travis-ci.org/findologic/findologic-api.svg?branch=master)](https://travis-ci.org/findologic/findologic-api)
[![Maintainability](https://api.codeclimate.com/v1/badges/b7efba0a8475fc2095cc/maintainability)](https://codeclimate.com/github/findologic/findologic-api/maintainability)
[![codecov](https://codecov.io/gh/findologic/findologic-api/branch/master/graph/badge.svg)](https://codecov.io/gh/findologic/findologic-api)
[![Packagist](https://img.shields.io/packagist/v/findologic/findologic-api.svg)](https://packagist.org/packages/findologic/findologic-api)

## Table of Contents

1. [Synopsis](#synopsis)
    1. [Limitations](#limitations)
1. [Requirements](#requirements)
1. [Installation](#installation)
1. [Basic usage](#basic-usage)
1. [Examples](#examples)
1. [Found a bug?](#found-a-bug)
1. [Contributing](#contributing)

## Synopsis

FINDOLOGIC-API is an object oriented wrapper for the Findologic API, with over 300 automated unit-tests and 100% code coverage.

This library not only helps requesting the Findologic API, but also getting data from the response and mapping them to corresponding objects.
You won't have to mess around with sending requests and getting the data from the Findologic's response anymore.  

You want to get filters? Just call `->getMainFilters()` on your response object. It really is that simple and if you dont trust us,
try out the [Basic Usage](#basic-usage) or test the [Examples](#examples).

To have a better understanding about the API, please make sure to read the general Findologic API documentation. We already got you covered with quicklinks to it:

 * [Requesting the API](https://docs.findologic.com/doku.php?id=integration_documentation:request)
 * [XML response](https://docs.findologic.com/doku.php?id=integration_documentation:response_xml)
 * JSON response (no docs available yet)

### Limitations

Currently, we support the following response formats:

| Response Type     | Format | Version | Supported                                        | End of life                   |
|-------------------|--------|---------|--------------------------------------------------|-------------------------------|
| Search/Navigation | JSON   | 1.0     | :heavy_check_mark:                               | Not in the foreseeable future |
|                   | XML    | 2.1     | :heavy_check_mark:                               | Not in the foreseeable future |
|                   | XML    | 2.0     | :heavy_multiplication_x: → Use XML_2.1 instead   | 2019-10-18                    |
|                   | HTML   | any     | :heavy_check_mark: →  The response is not parsed | Not in the foreseeable future |
| Smart Suggest     | JSON   | latest  | :heavy_check_mark:                               | Not in the foreseeable future |

## Requirements

 * [PHP](https://php.net/) >= 5.6
    * [PHP curl extension](https://www.php.net/manual/en/curl.installation.php) (optional)
 * [Composer](https://getcomposer.org/)

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
use FINDOLOGIC\Api\Responses\Xml21\Xml21Response;

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
    ->setRevision('1.0.0') // Version of your API wrapper
    ->setOutputAdapter('JSON_1.0'); // Optional setting of output format.

/** @var Json10Response $jsonResponse */
$jsonResponse = $client->send($searchRequest);

var_dump($jsonResponse->getResult()->getItems()); // Get all products.
var_dump($jsonResponse->getResult()->getMainFilters()); // Get all main filters easily.
var_dump($jsonResponse->getResult()->getOtherFilters()); // Get all other filters easily.
var_dump($jsonResponse); // Entire response, full of helper methods.
```

## Examples

* Working examples can be found in the
[`/examples`](https://github.com/findologic/findologic-api/tree/master/examples) directory.
* The documentation can be found in our
[Project Wiki](https://github.com/findologic/findologic-api/wiki).

## Found a bug?

We need your help! If you find any bug, please submit an issue and use our template! Be as precise as possible
so we can reproduce your case easier. For further information, please refer to our issue template at
[.github/ISSUE_TEMPLATE/bug_report.md](.github/ISSUE_TEMPLATE/bug_report.md).

## Contributing

Please check [our contribution guide](contributing.md) on how to contribute.
