<?php

namespace FINDOLOGIC\Api\Requests\SearchNavigation;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\Validators\ParameterValidator;

class NavigationRequest extends SearchNavigationRequest
{
    protected $endpoint = Endpoint::NAVIGATION;

    /**
     * Sets the selected param. It is used to tell FINDOLOGIC on which navigation page the user is.
     *
     * @param $filterName string
     * @param $value string
     * @return $this
     */
    public function setSelected($filterName, $value)
    {
        $validator = new ParameterValidator([
            'filterName' => $filterName,
            'value' => $value,
        ]);

        $validator
            ->rule('string', 'filterName')
            ->rule('stringOrNumeric', 'value');

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::SELECTED);
        }

        $this->addParam(QueryParameter::SELECTED, [$filterName => [$value]]);
        return $this;
    }
}
