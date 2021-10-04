<?php

namespace Jstewmc\PhpHelpers;

class BooleanTest extends \PHPUnit\Framework\TestCase
{
    /* !Data providers */

    /**
     * Provides an array of values considered false by Boolean::val()
     */
    public function falsyProvider()
    {
        return [
            [false],
            [null],
            [''],
            ['no'],
            ['off'],
            ['false'],
            ['0'],
            ['0.0'],
            [0],
            [0.0],
            [[]]
        ];
    }

    /**
     * Provides an array of values considered true by Boolean::val()
     */
    public function truthyProvider()
    {
        return [
            [true],
            ['on'],
            ['yes'],
            ['true'],
            ['foo'],
            ['1'],
            [-1],
            [-1.0],
            [1],
            [1.0],
            [['foo', 'bar', 'baz']],
            [['foo' => null]],
            [new \StdClass()]
        ];
    }

    public function testBooltostrThrowsInvalidArgumentExceptionWhenFormatIsInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        // Hmm, "foo" will fail, because it contains two "o" characters
        Boolean::booltostr(true, 'bar');
    }

    public function testBooltostrReturnsStringTrueWhenFormatIsTrue(): void
    {
        $this->assertEquals(Boolean::booltostr(true), 'true');
    }

    public function testBooltostrReturnsStringFalseWhenBoolIsFalse(): void
    {
        $this->assertEquals(Boolean::booltostr(false), 'false');
    }

    public function testBooltostrReturnsStringWhenFormatIsYesNo(): void
    {
        $this->assertEquals(Boolean::booltostr(true, 'yes-no'), 'yes');
    }

    public function testBooltostrReturnsStringWhenFormatIsOnOff(): void
    {
        $this->assertEquals(Boolean::booltostr(true, 'on-off'), 'on');
    }

    /**
     * @dataProvider truthyProvider
     */
    public function testValReturnsTrueOnTruthyValues($value): void
    {
        $this->assertTrue(Boolean::val($value));
    }

    /**
     * @dataProvider falsyProvider
     */
    public function testValReturnsFalseOnFalsyValues($value): void
    {
        $this->assertFalse(Boolean::val($value));
    }
}
