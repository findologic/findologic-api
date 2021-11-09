<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Item\Properties;

class ItemError
{
    private string $id;

    /** @var string[] */
    private array $reasons;

    /**
     * @param string[] $reasons
     */
    public function __construct(string $id, array $reasons)
    {
        $this->id = $id;
        $this->reasons = $reasons;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string[]
     */
    public function getReasons(): array
    {
        return $this->reasons;
    }
}
