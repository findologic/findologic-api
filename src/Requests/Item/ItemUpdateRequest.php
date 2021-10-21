<?php

namespace FINDOLOGIC\Api\Requests\Item;

use BadMethodCallException;
use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Requests\Request;

class ItemUpdateRequest extends Request
{
    protected $endpoint = Endpoint::UPDATE;
    protected $method = Request::METHOD_PATCH;

    private $rawBody = [];

    public function __construct(array $params = [])
    {
        parent::__construct($params);

        $this->body = json_encode(['update' => []]);
    }

    /**
     * @param string $productId
     * @param string $userGroup
     */
    public function markInvisible($productId, $userGroup = '')
    {
        $this->addUpdate($productId, [
            'visible' => [
                $userGroup => false
            ]
        ]);
    }

    /**
     * @param string $productId
     * @param string $userGroup
     */
    public function markVisible($productId, $userGroup = '')
    {
        $this->addUpdate($productId, [
            'visible' => [
                $userGroup => true
            ]
        ]);
    }

    /**
     * @param string $productId
     * @param float $price
     * @param string $userGroup
     */
    public function setPrice($productId, $price, $userGroup = '')
    {
        $this->addUpdate($productId, [
            'price' => [
                $userGroup => $price
            ]
        ]);
    }

    private function addUpdate($productId, array $changes)
    {
        if (!isset($this->rawBody['update'])) {
            $this->rawBody['update'] = [];
        }
        if (!isset($this->rawBody['update'][$productId])) {
            $this->rawBody['update'][$productId] = $changes;
            return;
        }

        $this->rawBody['update'][$productId] = array_merge_recursive($this->rawBody['update'][$productId], $changes);
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
        return json_encode($this->rawBody);
    }
}
