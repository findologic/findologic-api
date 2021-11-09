<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Definitions;

class QueryParameter extends Definition
{
    public const SERVICE_ID = 'shopkey';
    public const SHOP_URL = 'shopurl';
    public const USER_IP = 'userip';
    public const REFERER = 'referer';
    public const REVISION = 'revision';
    public const QUERY = 'query';
    public const ATTRIB = 'attrib';
    public const ORDER = 'order';
    public const PROPERTIES = 'properties';
    public const PUSH_ATTRIB = 'pushAttrib';
    public const COUNT = 'count';
    public const FIRST = 'first';
    public const IDENTIFIER = 'identifier';
    public const GROUP = 'group';
    public const USERGROUP = 'usergrouphash';
    public const FORCE_ORIGINAL_QUERY = 'forceOriginalQuery';
    public const OUTPUT_ATTRIB = 'outputAttrib';
    public const SELECTED = 'selected';
    public const OUTPUT_ADAPTER = 'outputAdapter';
}
