<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests\Helpers;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use PHPUnit\Framework\TestCase;
use stdClass;

class ResponseHelperTest extends TestCase
{
    private stdClass $testObject;

    public function setUp(): void
    {
        $this->testObject = new stdClass();
    }

    public function testGetPropertyReturnsNullIfPropertyDoesNotExist(): void
    {
        $propertyValue = ResponseHelper::getStringProperty($this->testObject, 'iDoNotExist');
        $this->assertNull($propertyValue);
    }

    public function testGetPropertyReturnsThePropertyIfSet(): void
    {
        $expectedPropertyValue = 'blubbergurken';
        $this->testObject->something = $expectedPropertyValue;
        $propertyValue = ResponseHelper::getStringProperty($this->testObject, 'something');
        $this->assertSame($expectedPropertyValue, $propertyValue);
    }

    public function testGetPropertyWillThrowAnExceptionIfSubmittingAnUncastableType(): void
    {
        $expectedPropertyValue = new stdClass();
        $this->testObject->something = $expectedPropertyValue;
        try {
            $propertyValue = ResponseHelper::getIntProperty($this->testObject, 'something');
            $this->fail('An Exception was expected to occur if an uncastable type is submitted.');
        } catch (\Exception $e) {
            $this->assertEquals('Object of class stdClass could not be converted to int', $e->getMessage());
        }
    }

    public function testGetStringPropertyWillAlwaysReturnAString(): void
    {
        $this->testObject->something = 1337;
        $this->assertSame('1337', ResponseHelper::getStringProperty($this->testObject, 'something'));
    }

    public function testGetIntPropertyWillAlwaysReturnAnInt(): void
    {
        $this->testObject->something = '1337';
        $this->assertSame(1337, ResponseHelper::getIntProperty($this->testObject, 'something'));
    }

    public function testGetFloatPropertyWillAlwaysReturnAFloat(): void
    {
        $this->testObject->something = '13.37';
        $this->assertSame(13.37, ResponseHelper::getFloatProperty($this->testObject, 'something'));
    }

    /**
     * @return array<string, array<string, bool|string>>
     */
    public function stringAsBoolProvider(): array
    {
        return [
            '"0" as string' => [
                'originalObjectValue' => '0',
                'expectedResult' => false,
            ],
            '"1" as string' => [
                'originalObjectValue' => '1',
                'expectedResult' => true,
            ],
        ];
    }

    /**
     * @dataProvider stringAsBoolProvider
     */
    public function testGetBoolPropertyWillAlwaysReturnABool(string $originalObjectValue, bool $expectedResult): void
    {
        $this->testObject->something = $originalObjectValue;
        $this->assertSame($expectedResult, ResponseHelper::getBoolProperty($this->testObject, 'something'));
    }

    /**
     * @return array<string, array<string, float|int|string|bool>>
     */
    public function nonArrayObjectProvider(): array
    {
        return [
            'passing a string as object' => [
                'data' => 'i am just a string :('
            ],
            'passing a bool as object' => [
                'data' => true
            ],
            'passing an int as object' => [
                'data' => 420
            ],
            'passing a float as object' => [
                'data' => 4.20
            ],
        ];
    }

    /**
     * @dataProvider nonArrayObjectProvider
     * @param string|bool|int|float $data
     */
    public function testCallingResponseHelperWithNoArrayAndNoObjectReturnsNull($data): void
    {
        $this->assertNull(ResponseHelper::getBoolProperty($data, 'does-not-matter'));
        $this->assertNull(ResponseHelper::getFloatProperty($data, 'does-not-matter'));
        $this->assertNull(ResponseHelper::getIntProperty($data, 'does-not-matter'));
        $this->assertNull(ResponseHelper::getStringProperty($data, 'does-not-matter'));
    }
}
