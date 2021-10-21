<?php

namespace FINDOLOGIC\Api\Responses\Item\Properties;

class ItemError
{
    /** @var string */
    private $id;

    /** @var string[] */
    private $reasons;

    /**
     * @param string $id
     * @param string[] $reasons
     */
    public function __construct($id, $reasons)
    {
        $this->id = $id;
        $this->reasons = $reasons;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string[]
     */
    public function getReasons()
    {
        return $this->reasons;
    }
}
