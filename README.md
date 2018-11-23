# FINDOLOGIC API

[![Travis](https://travis-ci.org/TheKeymaster/findologic-api.svg?branch=master)](https://travis-ci.org/TheKeymaster/findologic-api)
[![Maintainability](https://api.codeclimate.com/v1/badges/d604675c46586292c20f/maintainability)](https://codeclimate.com/github/TheKeymaster/findologic-api/maintainability)
[![codecov](https://codecov.io/gh/TheKeymaster/findologic-api/branch/master/graph/badge.svg)](https://codecov.io/gh/TheKeymaster/findologic-api)

**Please note**: This repository is still WIP and therefore either not usable or only partially usable. Use it at your own risk!

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
Since we are _not yet_ on Packagist you might want to require this library via [Composer VCS](https://getcomposer.org/doc/05-repositories.md#vcs):

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/TheKeymaster/findologic-api"
        }
    ],
    "require": {
        "TheKeymaster/findologic-api": "dev-master"
    }
}
```

Usually the `master` branch should be pretty stable, but this will change in the future as soon as we have our first real stable version.

## Basic usage

The usage is pretty simple. Here is an example:

```php
// Examples will be added in a future release.
```

## Examples

Soon™

## Requirements

 * [PHP 5.6 or higher](https://php.net/) older versions are not supported.
 * [Composer](https://getcomposer.org/)

## Found a bug?

We need your help! If you find any bug, please submit an issue and use our template! Be as precise as possible
so we can reproduce your case easier. For further information, please refer to our issue template at
`.github/ISSUE_TEMPLATE/bug_report.md`.
