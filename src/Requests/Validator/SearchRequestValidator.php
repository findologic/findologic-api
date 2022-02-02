<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\Validator;

use FINDOLOGIC\Api\Definitions\OrderType;
use FINDOLOGIC\Api\Definitions\OutputAdapter;
use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\Requests\Validator\Rule\AttributeRule;
use FINDOLOGIC\Api\Requests\Validator\Rule\SemanticVersionRule;
use FINDOLOGIC\Api\Requests\Validator\Rule\ServiceIdRule;
use FINDOLOGIC\Api\Requests\Validator\Rule\StringRule;
use Psr\Http\Message\RequestInterface;
use Rakit\Validation\Validation;
use Rakit\Validation\Validator;

class SearchRequestValidator extends RequestValidator
{
    private Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator();
    }

    /**
     * @throws \Rakit\Validation\RuleNotFoundException
     */
    public function makeValidation(RequestInterface $request): Validation
    {
        $queryParams = $this->parseQueryParams($request);

        $this->addCustomValidatorRules();
        $validation = $this->validator->make($queryParams, [
            QueryParameter::SERVICE_ID => 'required|serviceId',
            QueryParameter::USER_IP => 'required|ip',
            QueryParameter::REVISION => 'required|version',
            QueryParameter::SHOP_URL => 'url',

            QueryParameter::QUERY => 'string',
            QueryParameter::FIRST => 'numeric|min:0',
            QueryParameter::COUNT => 'numeric|min:0',
            QueryParameter::GROUP => 'array',
            QueryParameter::GROUP . '.*' => 'string',
            QueryParameter::USERGROUP => 'array',
            QueryParameter::USERGROUP . '.*' => 'array',
            QueryParameter::OUTPUT_ADAPTER => [($this->validator)('in', OutputAdapter::getConstants())->strict()],

            QueryParameter::REFERER => 'url',
            QueryParameter::ATTRIB => 'attribute',
            QueryParameter::ORDER => [($this->validator)('in', OrderType::getConstants())->strict()],
            QueryParameter::PROPERTIES => 'array',
            QueryParameter::PROPERTIES . '.*' => 'string',
            QueryParameter::PUSH_ATTRIB => 'array',
            QueryParameter::IDENTIFIER => 'string',
            QueryParameter::OUTPUT_ATTRIB => 'array',
            QueryParameter::OUTPUT_ATTRIB . '.*' => 'string',
            QueryParameter::FORCE_ORIGINAL_QUERY => 'boolean',
        ]);

        $this->setAliases($validation);

        return $validation;
    }

    private function addCustomValidatorRules(): void
    {
        $this->validator->addValidator('string', new StringRule());

        $this->validator->addValidator('serviceId', new ServiceIdRule());
        $this->validator->addValidator('version', new SemanticVersionRule());
        $this->validator->addValidator('attribute', new AttributeRule());
    }

    private function setAliases(Validation $validation): void
    {
        $validation->setAliases([
            'shopkey' => 'Service ID (shopkey)'
        ]);
    }
}
