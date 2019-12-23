<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use Countable;

abstract class Collection implements Countable
{
    /** @var string[] */
    private $entities;

    public function __construct(array $entities)
    {
        foreach ($entities as $key => $value) {
            $this->entities[$key] = $value;
        }
    }

    /**
     * Gets an entity by the array key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (!isset($this->entities[$key])) {
            return $default;
        }

        return $this->entities[$key];
    }

    /**
     * @return string[]
     */
    public function all()
    {
        return $this->entities;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->entities);
    }
}