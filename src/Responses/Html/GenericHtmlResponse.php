<?php

namespace FINDOLOGIC\Api\Responses\Html;

use FINDOLOGIC\Api\Responses\Response;

/**
 * The returned HTML varies strongly since everyone can configure their own returned HTML by changing
 * it in the template manager. This generic HTML response therefore only allows to get the raw
 * response contents.
 */
class GenericHtmlResponse extends Response
{
    protected function buildResponseElementInstances($response)
    {
        // Nothing to do here.
    }
}
