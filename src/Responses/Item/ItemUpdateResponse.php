<?php

namespace FINDOLOGIC\Api\Responses\Item;

use FINDOLOGIC\Api\Responses\Item\Properties\ItemError;
use FINDOLOGIC\Api\Responses\Response;

class ItemUpdateResponse extends Response
{
    /** @var ItemError[] */
    private $errors = [];

    protected function buildResponseElementInstances($response)
    {
        $contents = json_decode($response, true);

        foreach ($contents['errors'] as $itemId => $reasons) {
            $this->errors[] = new ItemError($itemId, $reasons);
        }
    }

    /**
     * @see ItemUpdateResponse::getErrors()
     * @return bool
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    /**
     * @see ItemUpdateResponse::hasErrors()
     * @return ItemError[]
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
