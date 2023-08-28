<?php

namespace App\Tests\Unit\Service\Math;

use PHPUnit\Framework\TestCase;
use App\Service\Math\Calculator;

class CalculatorTest extends TestCase
{
    /**
     * @var Calculator
     */
    private Calculator $calculator;

    /**
     * @return array
     */
    static public function validateDataProvider(): array
    {
        return [
            'zero' => ['0', true],
            'positive int' => ['130', true],
            'negative int' => ['-88', true],
            'positive float' => ['74.12056', true],
            'negative float' => ['-.990', true],
            'comma separator' => ['28,25', false],
            'double zero' => ['00.1', false],
            'invalid character' => ['14f', false]
        ];
    }

    /**
     * @return array
     */
    static public function ceilingDataProvider(): array
    {
        return [
            'int to higher' => ['13', 1, '13.0'],
            'float to same' => ['0.12', 2, '0.12'],
            'float to higher' => ['3.33', 4, '3.3300'],
            'float to lower' => ['66.3701', 2, '66.38'],
            'float to lower same' => ['1.5670', 3, '1.567']
        ];
    }

    /**
     * @param string $number
     * @param bool $result
     * 
     * @dataProvider validateDataProvider
     */
    public function testValidate(string $number, bool $result): void
    {
        $this->assertSame($result, $this->calculator->validate($number));
    }

    /**
     * @param string $number
     * @param int $precision
     * @param string $result
     * 
     * @dataProvider ceilingDataProvider
     */
    public function testCeiling(string $number, int $precision, string $result): void
    {
        $this->assertSame($result, $this->calculator->ceiling($number, $precision));
    }

    public function testCeilingInvalidNumber(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid number.');

        $this->calculator->ceiling('a', 1);
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->calculator = new Calculator();
    }
}