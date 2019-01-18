<?php

namespace FINDOLOGIC\Tests\Helpers;

use FINDOLOGIC\Helpers\ResponseHelper;
use PHPUnit\Framework\TestCase;
use stdClass;

class ResponseHelperTest extends TestCase
{
    private $testObject;

    public function setUp()
    {
        $this->testObject = new stdClass();
    }

    public function testGetPropertyReturnsNullIfPropertyDoesNotExist()
    {
        $propertyValue = ResponseHelper::getProperty($this->testObject, 'iDoNotExist', 'string');
        $this->assertNull($propertyValue);
    }

    public function testGetPropertyReturnsThePropertyIfSet()
    {
        $expectedPropertyValue = 'blubbergurken';
        $this->testObject->something = $expectedPropertyValue;
        $propertyValue = ResponseHelper::getProperty($this->testObject, 'something', 'string');
        $this->assertSame($expectedPropertyValue, $propertyValue);
    }

    public function testGetPropertyReturnsPropertyIfSetAndNoTypeIsSubmitted()
    {
        $expectedPropertyValue = 42;
        $this->testObject->something = $expectedPropertyValue;
        $propertyValue = ResponseHelper::getProperty($this->testObject, 'something');
        $this->assertSame($expectedPropertyValue, $propertyValue);
        $this->assertTrue(is_int($propertyValue));
    }

    public function testGetPropertyWillThrowAnExceptionIfSubmittingAnUncastableType()
    {
        $expectedPropertyValue = new stdClass();
        $this->testObject->something = $expectedPropertyValue;
        try {
            $propertyValue = ResponseHelper::getProperty($this->testObject, 'something', 'int');
            $this->fail('An Exception was expected to occur if an uncastable type is submitted.');
        } catch (\Exception $e) {
            $this->assertEquals('Object of class stdClass could not be converted to int', $e->getMessage());
        }
    }

    public function testGetPropertyWillThrowAnExceptionIfSubmittingAnUnknownType()
    {
        $expectedPropertyValue = 'blubbergurken';
        $this->testObject->something = $expectedPropertyValue;
        try {
            $propertyValue = ResponseHelper::getProperty($this->testObject, 'something', 'olo');
            $this->fail('An Exception was expected to occur if an unknown type is submitted.');
        } catch (\Exception $e) {
            $this->assertEquals('settype(): Invalid type', $e->getMessage());
        }
    }
}
