<?php

namespace FINDOLOGIC\Api\Requests\Item\Changes;

use FINDOLOGIC\Api\Definitions\Defaults;

abstract class Change
{
    /** @var string */
    private $userGroup;

    public function __construct($userGroup = Defaults::USER_GROUP)
    {
        $this->userGroup = $userGroup;
    }

    /**
     * Must return the key name as described in the API spec.
     *
     * @return string
     */
    abstract public function getKey();

    /**
     * Must return the value for the specified change as described in the API spec.
     *
     * @return mixed
     */
    abstract public function getValue();

    /**
     * @return string
     */
    public function getUserGroup()
    {
        return $this->userGroup;
    }
}
