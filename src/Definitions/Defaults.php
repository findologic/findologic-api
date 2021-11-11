<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Definitions;

class Defaults extends Definition
{
    /** @var string Default usergroup aka. no usergroup */
    public const USER_GROUP = '';
    public const RANGE_STEP_SIZE = 0.1;
    public const CURRENCY = '€';
    public const EMPTY = '';
}
