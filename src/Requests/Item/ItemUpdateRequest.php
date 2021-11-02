<?php

namespace FINDOLOGIC\Api\Requests\Item;

use BadMethodCallException;
use FINDOLOGIC\Api\Definitions\Defaults;
use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Requests\Item\Changes\PriceChange;
use FINDOLOGIC\Api\Requests\Item\Changes\VisibilityChange;
use FINDOLOGIC\Api\Requests\Request;
use InvalidArgumentException;

class ItemUpdateRequest extends Request
{
    protected $endpoint = Endpoint::UPDATE;
    protected $method = Request::METHOD_PATCH;

    /** @var Item[] */
    private $items = [];

    /**
     * @param string $id
     * @param string $userGroup
     */
    public function markInvisible($id, $userGroup = Defaults::USER_GROUP)
    {
        $item = $this->getOrCreateItem($id);

        /** @var VisibilityChange $change */
        $change = $item->getOrCreateChange(Item::VISIBILITY_CHANGE, $userGroup);
        $change->setInvisible();

        $item->addChange($change);

        $this->items[$item->getId()] = $item;
    }

    /**
     * @param string $id
     * @param string $userGroup
     */
    public function markVisible($id, $userGroup = Defaults::USER_GROUP)
    {
        $item = $this->getOrCreateItem($id);

        /** @var VisibilityChange $change */
        $change = $item->getOrCreateChange(Item::VISIBILITY_CHANGE, $userGroup);
        $change->setVisible();

        $item->addChange($change);

        $this->items[$item->getId()] = $item;
    }

    /**
     * @param string $id
     * @param float $price
     * @param string $userGroup
     */
    public function setPrice($id, $price, $userGroup = Defaults::USER_GROUP)
    {
        $item = $this->getOrCreateItem($id);

        /** @var PriceChange $change */
        $change = $item->getOrCreateChange(Item::PRICE_CHANGE, $userGroup);
        $change->setPrice($price);

        $item->addChange($change);

        $this->items[$item->getId()] = $item;
    }

    public function reset()
    {
        $this->items = [];
    }

    /**
     * @param string $id
     */
    public function resetItemChanges($id)
    {
        $this->findItem($id)->resetChanges();
    }

    /**
     * @param string $id
     * @return Item
     */
    public function getItem($id)
    {
        return $this->findItem($id);
    }

    /**
     * @param string $id
     * @return Item
     */
    private function findItem($id)
    {
        if (!isset($this->items[$id])) {
            throw new InvalidArgumentException(sprintf(
                'Could not find item with id "%s"',
                $id
            ));
        }

        return $this->items[$id];
    }

    private function getOrCreateItem($id)
    {
        if (isset($this->items[$id])) {
            return $this->items[$id];
        }

        return new Item($id);
    }

    public function getOutputAdapter()
    {
        return null;
    }

    public function setQuery($value)
    {
        throw new BadMethodCallException('Parameter "query" is not supported for item updates');
    }

    public function setCount($value)
    {
        throw new BadMethodCallException('Parameter "count" is not supported for item updates');
    }

    public function addGroup($value)
    {
        throw new BadMethodCallException('Parameter "group" is not supported for item updates');
    }

    public function addUserGroup($value)
    {
        throw new BadMethodCallException('Parameter "usergroup" is not supported for item updates');
    }

    public function setOutputAdapter($value)
    {
        throw new BadMethodCallException('Parameter "outputAdapter" is not supported for item updates');
    }

    public function getBody()
    {
        $body = [];
        $body['update'] = [];

        foreach ($this->items as $item) {
            $body['update'][$item->getId()] = [];

            foreach ($item->getChanges() as $change) {
                $body['update'][$item->getId()][$change->getKey()][$change->getUserGroup()] = $change->getValue();
            }
        }

        return json_encode($body);
    }
}
