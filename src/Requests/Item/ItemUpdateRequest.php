<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\Item;

use BadMethodCallException;
use FINDOLOGIC\Api\Definitions\Defaults;
use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\RequestMethod;
use FINDOLOGIC\Api\Requests\Item\Changes\PriceChange;
use FINDOLOGIC\Api\Requests\Item\Changes\VisibilityChange;
use FINDOLOGIC\Api\Requests\Request;
use InvalidArgumentException;

class ItemUpdateRequest extends Request
{
    protected string $endpoint = Endpoint::UPDATE;
    protected string $method = RequestMethod::PATCH;

    /** @var Item[] */
    private array $items = [];

    public function markInvisible(string $id, string $userGroup = Defaults::USER_GROUP): void
    {
        $item = $this->getOrCreateItem($id);

        /** @var VisibilityChange $change */
        $change = $item->getOrCreateChange(Item::VISIBILITY_CHANGE, $userGroup);
        $change->setInvisible();

        $item->addChange($change);

        $this->items[$item->getId()] = $item;
    }

    public function markVisible(string $id, string $userGroup = Defaults::USER_GROUP): void
    {
        $item = $this->getOrCreateItem($id);

        /** @var VisibilityChange $change */
        $change = $item->getOrCreateChange(Item::VISIBILITY_CHANGE, $userGroup);
        $change->setVisible();

        $item->addChange($change);

        $this->items[$item->getId()] = $item;
    }

    public function setPrice(string $id, float $price, string $userGroup = Defaults::USER_GROUP): void
    {
        $item = $this->getOrCreateItem($id);

        /** @var PriceChange $change */
        $change = $item->getOrCreateChange(Item::PRICE_CHANGE, $userGroup);
        $change->setPrice($price);

        $item->addChange($change);

        $this->items[$item->getId()] = $item;
    }

    public function reset(): void
    {
        $this->items = [];
    }

    public function resetItemChanges(string $id): void
    {
        $this->findItem($id)->resetChanges();
    }

    public function getItem(string $id): Item
    {
        return $this->findItem($id);
    }

    private function findItem(string $id): Item
    {
        if (!isset($this->items[$id])) {
            throw new InvalidArgumentException(sprintf(
                'Could not find item with id "%s"',
                $id
            ));
        }

        return $this->items[$id];
    }

    private function getOrCreateItem(string $id): Item
    {
        if (isset($this->items[$id])) {
            return $this->items[$id];
        }

        return new Item($id);
    }

    public function getOutputAdapter(): ?string
    {
        return null;
    }

    public function setQuery(string $value): self
    {
        throw new BadMethodCallException('Parameter "query" is not supported for item updates');
    }

    public function setCount(int $value): self
    {
        throw new BadMethodCallException('Parameter "count" is not supported for item updates');
    }

    public function addGroup(string $value): self
    {
        throw new BadMethodCallException('Parameter "group" is not supported for item updates');
    }

    public function addUserGroup(string $value): self
    {
        throw new BadMethodCallException('Parameter "usergroup" is not supported for item updates');
    }

    public function setOutputAdapter(string $value): self
    {
        throw new BadMethodCallException('Parameter "outputAdapter" is not supported for item updates');
    }

    public function getBody(): string
    {
        $body = [];
        $body['update'] = [];

        foreach ($this->items as $item) {
            $body['update'][$item->getId()] = [];

            foreach ($item->getChanges() as $change) {
                $body['update'][$item->getId()][$change->getKey()][$change->getUserGroup()] = $change->getValue();
            }
        }

        if (!$json = json_encode($body)) {
            throw new \Exception('Something went wrong while trying to build the JSON body');
        }

        return $json;
    }
}
