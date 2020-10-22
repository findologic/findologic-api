<?php

namespace FINDOLOGIC\Api\Requests\Parameters;

class ShopkeyParameter extends SimpleParameter
{
    public function __construct($shopkey)
    {
        parent::__construct('shopkey', $shopkey);

        $this->setValidationRules([
            $this->buildRule('regex', '/^[A-F0-9]{32}$/')
        ]);
    }
}
