<?php

namespace FINDOLOGIC\Api\Tests\Requests\Parameters;

use FINDOLOGIC\Api\Requests\Parameters\ParameterBag;
use FINDOLOGIC\Api\Requests\Parameters\ShopkeyParameter;
use FINDOLOGIC\Api\Requests\Parameters\SimpleParameter;
use FINDOLOGIC\Api\Tests\TestBase;

class ParameterBagTest extends TestBase
{
    /** @var ParameterBag */
    private $parameterBag;

    protected function setUp()
    {
        parent::setUp();

        $this->parameterBag = new ParameterBag();
    }

    public function testAddsAndGetsParameter()
    {
        $expectedParameterName = 'shopkey';
        $expectedParameterValue = 'xd';

        $parameter = new ShopkeyParameter($expectedParameterValue);
        $this->parameterBag->add($parameter);

        $actualParam = $this->parameterBag->get($expectedParameterName);
        $this->assertSame($expectedParameterName, $actualParam->getName());
        $this->assertSame($expectedParameterValue, $actualParam->getValue());
    }
}
