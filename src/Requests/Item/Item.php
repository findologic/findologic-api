<?php

declare(strict_types=1);

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
    private string $id;
    /** @var Change[] */
    private array $changes = [];

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function addChange(Change $change): void
    {
        $this->changes[] = $change;
    }

    public function resetChanges(): void
    {
        $this->changes = [];
    }

    /**
     * @return Change[]
     */
    public function getChanges(): array
    {
        return $this->changes;
    }

    public function getOrCreateChange(int $type, string $userGroup = Defaults::USER_GROUP): Change
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
