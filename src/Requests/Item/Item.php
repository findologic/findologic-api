<?php

namespace FINDOLOGIC\Api\Requests\Item;

use FINDOLOGIC\Api\Definitions\Defaults;
use FINDOLOGIC\Api\Requests\Item\Changes\Change;
use FINDOLOGIC\Api\Requests\Item\Changes\PriceChange;
use FINDOLOGIC\Api\Requests\Item\Changes\VisibilityChange;
use InvalidArgumentException;

class Item
{
    public const VISIBILITY_CHANGE = 0;
    public const PRICE_CHANGE = 1;

    /** @var string */
    private $id;

    /** @var Change[] */
    private $changes = [];

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    public function addChange(Change $change)
    {
        $this->changes[] = $change;
    }

    public function resetChanges()
    {
        $this->changes = [];
    }

    /**
     * @return Change[]
     */
    public function getChanges()
    {
        return $this->changes;
    }

    /**
     * @param int $type
     * @param string $userGroup
     * @return Change
     */
    public function getOrCreateChange($type, $userGroup = Defaults::USER_GROUP)
    {
        switch ($type) {
            case self::VISIBILITY_CHANGE:
                $class = VisibilityChange::class;
                break;
            case self::PRICE_CHANGE:
                $class = PriceChange::class;
                break;
            default:
                throw new InvalidArgumentException('Unknown change type provided');
        }

        foreach ($this->changes as $change) {
            if ($change instanceof $class && $change->getUserGroup() === $userGroup) {
                return $change;
            }
        }

        return new $class($userGroup);
    }
}
