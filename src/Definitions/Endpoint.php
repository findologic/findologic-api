<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Definitions;

class Endpoint extends Definition
{
    public const ALIVETEST = '/alive';
    public const SEARCH = '/search';
    public const NAVIGATION = '/navigation';
    public const SUGGEST = '/suggest';
    public const UPDATE = '/update';
}
