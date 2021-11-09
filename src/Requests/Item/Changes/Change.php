<?php

namespace FINDOLOGIC\Api\Requests\Item\Changes;

use FINDOLOGIC\Api\Definitions\Defaults;

abstract class Change
{
    private string $userGroup;

    public function __construct(string $userGroup = Defaults::USER_GROUP)
    {
        $this->userGroup = $userGroup;
    }

    /**
     * Must return the key name as described in the API spec.
     */
    abstract public function getKey(): string;

    /**
     * Must return the value for the specified change as described in the API spec.
     *
     * @return mixed
     */
    abstract public function getValue();

    public function getUserGroup(): string
    {
        return $this->userGroup;
    }
}
