<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Item;

use FINDOLOGIC\Api\Responses\Item\Properties\ItemError;
use FINDOLOGIC\Api\Responses\Response;

class ItemUpdateResponse extends Response
{
    /** @var ItemError[] */
    private array $errors = [];

    protected function buildResponseElementInstances(string $response): void
    {
        $contents = json_decode($response, true);

        foreach ($contents['errors'] as $itemId => $reasons) {
            $this->errors[] = new ItemError((string)$itemId, $reasons);
        }
    }

    /**
     * @see ItemUpdateResponse::getErrors()
     */
    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    /**
     * @see ItemUpdateResponse::hasErrors()
     * @return ItemError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
