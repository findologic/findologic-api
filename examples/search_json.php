<?php

// This example shows a simple shop search without filters.

use FINDOLOGIC\Api\Client;
use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Requests\SearchNavigation\SearchRequest;
use FINDOLOGIC\Api\Responses\Json10\Json10Response;
use FINDOLOGIC\Api\Responses\Json10\Properties\Item;

const SERVICE_ID = '< SET YOUR SERVICE ID / SHOPKEY HERE >';

const ITEM_TEMPLATE = <<<EOL
<div class="col-3">
    <div class="card">
        <a href="%s">
            <img class="card-img-top mt-3" src="%s" alt="%s">
        </a>
        <div class="card-body">
            <h5 class="card-title">%s</h5>
            <p class="card-text">%s</p>
            <a href="%s" class="btn btn-primary">Buy</a>
        </div>
    </div>
</div>
EOL;

function fetchProducts()
{
    require_once __DIR__ . '/../vendor/autoload.php';

    $config = new Config(SERVICE_ID);
    $client = new Client($config);

    $searchRequest = new SearchRequest();
    $searchRequest
        ->setQuery(isset($_GET['query']) ? $_GET['query'] : '')
        ->setShopUrl('your-shop.com')
        ->setUserIp('127.0.0.1')
        ->setReferer('http://google.com/')
        ->setRevision('1.0.0')
        ->setOutputAdapter('JSON_1.0');

    /** @var Json10Response $response */
    $response = $client->send($searchRequest);

    return getRenderedProducts($response);
}

function getRenderedProducts(Json10Response $response)
{
    $rendered = '';
    $count = 0;
    foreach ($response->getResult()->getItems() as $item) {
        if ($count % 4 == 0 || $count === 0) {
            if ($count !== 0) {
                $rendered .= '</div>';
            }
            $rendered .= '<div class="row mb-4">';
        }

        $rendered .= wrapItem($item);

        $count++;
    }

    return $rendered;
}

function wrapItem(Item $item)
{
    return sprintf(
        ITEM_TEMPLATE,
        $item->getUrl(),
        $item->getImageUrl(),
        $item->getName(),
        $item->getName(),
        $item->getSummary(),
        $item->getUrl()
    );
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <title>FINDOLOGIC-API simple search example</title>
    <style>
        img.card-img-top {
            display: block;
            max-width: 100%;
            width: 100%;
            max-height: 200px;
            object-fit: contain;
        }
        button {
            width: 100%
        }
        .col-3 .card {
            min-height: 360px;
        }
        .card-text {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
    <div class="ml-5 mr-5">
        <h2>Simple search example</h2>
    </div>
    <div class="ml-5 mr-5">
        <form action="search_json.php">
            <div class="row">
                <div class="col-11">
                    <input class="form-control" type="text" placeholder="Search" aria-label="Search" name="query">
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
        <h5 class="mt-2">Search results for <strong><?php echo isset($_GET['query']) ? htmlentities($_GET['query']) : '' ?></strong></h5>
        <div>
            <?php echo fetchProducts(); ?>
        </div>
    </div>
</body>
</html>
