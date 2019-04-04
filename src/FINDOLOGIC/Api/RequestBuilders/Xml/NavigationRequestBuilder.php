<?php

namespace FINDOLOGIC\Api\RequestBuilders\Xml;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\ResponseObjects\Xml\XmlResponse;

class NavigationRequestBuilder extends XmlRequestBuilder
{
    protected $endpoint = Endpoint::NAVIGATION;
}
