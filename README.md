# FINDOLOGIC API

**Please note**: This repository is still WIP and therefor either not usable or only partially usable. Use it at your own risk!

## Synopsis

This repository helps requesting the FINDOLOGIC API and getting data from the response.
To have a better understanding about the API, please make sure to read the general FINDOLOGIC API documentation. Some quicklinks to the documentation:

 * [Requesting the API](https://docs.findologic.com/doku.php?id=integration_documentation:request).
 * [XML response](https://docs.findologic.com/doku.php?id=integration_documentation:response_xml).
 
## Usage

The usage is pretty simple. Here is a detailed example:

```php
require_once './vendor/autoload.php';

use \FINDOLOGIC\Request;

$request = new Request::create(Request::TYPE_SEARCH);

// Now add all required params.
$request->setShopkey('ABCDABCDABCDABCDABCDABCDABCDABCD');
$request->setShopurl('https://blubbergurken.de/');
$request->setUserip('10.0.0.1');
$request->setReferer('https://blubbergurken.de/somewhere/on/the/website');
$request->setRevision('1.0.0');

$response = $request->send();

$resultAmount = $response->getResultsCount();
$filters = $response->getAvailableFilters();
$products = $response->getAvailableProducts();

// Print some result amount text.
if (count($resultAmount) < 1) {
    echo 'There were no results found for your query.';
} else {
    // Print found products.
    echo sprintf('%d results were found for your query.', $resultAmount);
}

// Print some filters.
foreach ($filters as $filter) {
    echo $filter->getName();
    if ($filter->getType() === 'range-slider') {
        // Create some sort of slider.
    }
}

// Print some product data.
foreach ($products as $product) {
    $productId = $product->getId();
    // This may be shop specific. Map products to display a product in the shop.
    $shop->printArticleById($productId);
}
```

All response functions (e.g. filters and results) will return PHP arrays containing multiple PHP objects.

## Requirements

 * PHP >= 5.6.