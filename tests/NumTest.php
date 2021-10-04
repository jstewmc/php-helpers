<?php

namespace Jstewmc\PhpHelpers;

class NumTest extends \PHPUnit\Framework\TestCase
{
    public function provideFloatValues()
    {
        return [
            [-1.0],
            [0.0],
            [1.0],
            ['-1.0'],
            ['0.0'],
            ['1.0']
        ];
    }

    public function provideIntegerValues()
    {
        return [
            [-1],
            [0],
            [1],
            ['-1'],
            ['0'],
            ['1']
        ];
    }

    public function provideNonNumericValues()
    {
        return [
            [true],
            ['foo'],
            [[]],
            [new \StdClass()]
        ];
    }

    public function provideZeroValues(): array
    {
        return [
            [0],
            [0.0],
            ['0'],
            ['0.0']
        ];
    }

    public function testAlmostEqualThrowsInvalidArgumentExceptionWhenEpsilonIsNegative(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::almostEqual(1.0, 1.0, 0);
    }

    public function testAlmostEqualThrowsInvalidArgumentExceptionWhenEpsilonIsZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::almostEqual(1.0, 1.0, 0);
    }

    public function testAlmostEqualReturnsTrueWhenFloatsAreEqual(): void
    {
        $this->assertTrue(Num::almostEqual(1/10, 0.1));
    }

    public function testAlmostEqualReturnsFalseWhenFloatsAreUnequal(): void
    {
        $this->assertFalse(Num::almostEqual(0.2, 0.7));
    }

    public function testAlmostEqualReturnsTrueWhenIntegersAreEqual(): void
    {
        $this->assertTrue(Num::almostEqual(1, 1));
    }

    public function testAlmostEqualReturnsFalseWhenIntegersAreUnequal(): void
    {
        $this->assertFalse(Num::almostEqual(1, 2));
    }

    public function testBoundThrowsInvalidArgumentExceptionWheNumberIsNaN(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::bound('foo', 1, 0);
    }

    public function testBoundThrowsInvalidArgumentExceptionWheLowerBoundIsNaN(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::bound(1, 'foo', 0);
    }

    public function testBoundThrowsInvalidArgumentExceptionWheUpperBoundIsNaN(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::bound(1, 1, 'foo');
    }

    public function testBoundThrowsInvalidArgumentExceptionWhenLowerBoundIsGreaterThanUpperBound(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::bound(1, 2, 0);
    }

    public function testBoundReturnsUpperWhenNumberIsGreaterThanUpperBound(): void
    {
        $this->assertEquals(1, Num::bound(2, null, 1));
    }

    public function testBoundReturnsLowerWhenNumberIsLessThanLowerBound(): void
    {
        $this->assertEquals(2, Num::bound(1, 2));
    }

    public function testBoundReturnsNumberWhenNumberIsBetweenLowerBoundAndUpperBound(): void
    {
        $this->assertEquals(1, Num::bound(1, 0, 2));
    }

    public function testCeilToThrowsInvalidArgumentExceptionWhenNumberIsNaN(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::ceilTo('foo');
    }

    public function testCeilToThrowsInvalidArgumentExceptionWhenMultipleIsNaN(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::ceilTo(1, 'foo');
    }

    public function testCeilToThrowsInvalidArgumentExceptionWhenMultipleIsNegative(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::ceilTo(1, -1);
    }

    public function testCeilToThrowsInvalidArgumentExceptionWhenMultipleIsZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::ceilTo(1, 0);
    }

    public function testCeilToReturnsPhpNativeCeilWhenMultipleIsOmitted(): void
    {
        $this->assertEquals(ceil(2.5), Num::ceilTo(2.5));
    }

    public function testCeilToReturnsCeilingWhenNumberAndMultipleAreIntegers(): void
    {
        $this->assertEquals(10, Num::ceilTo(7, 10));
    }

    public function testCeilToReturnsCeilingWhenNumberAndMultipleAreFloats(): void
    {
        $this->assertEquals(4.5, Num::ceilTo(3.5, 1.5));
    }

    public function testCeilToReturnsCeilingWhenNumberIsIntegerAndMultipleIsFloat(): void
    {
        $this->assertEquals(3, Num::ceilTo(2, 1.5));
    }

    public function testCeilToReturnsCeilingWhenNumberIsFloatAndMultipleIsInteger(): void
    {
        $this->assertEquals(4, Num::ceilTo(2.5, 2));
    }

    public function testFloorToThrowsInvalidArgumentExceptionWhenNumberIsNaN(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::floorTo('foo');
    }

    public function testFloorToThrowsInvalidArgumentExceptionWhenMultipleIsNaN(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::floorTo(1, 'foo');
    }

    public function testFloorToThrowsInvalidArgumentExceptionWhenMultipleIsNegative(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::floorTo(1, -1);
    }

    public function testFloorToThrowsInvalidArgumentExceptionWhenMultipleIsZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::floorTo(1, 0);
    }

    public function testFloorToReturnsPhpNativeFloorWhenMultipleIsOmitted(): void
    {
        $this->assertEquals(floor(2.5), Num::floorTo(2.5));
    }

    public function testFloorToReturnsFloorWhenNumberAndMultipleAreIntegers(): void
    {
        $this->assertEquals(4, Num::floorTo(5, 2));
    }

    public function testFloorToReturnsFloorWhenNumberAndMultipleAreFloats(): void
    {
        $this->assertEquals(3, Num::floorTo(3.5, 1.5));
    }

    public function testFloorToReturnsFloorWhenNumberIsIntegerAndMultipleIsFloat(): void
    {
        $this->assertEquals(1.5, Num::floorTo(2, 1.5));
    }

    public function testFloorToReturnsFloorWhenNumberIsFloatAndMultipleIsInteger(): void
    {
        $this->assertEquals(2, Num::floorTo(2.5, 2));
    }

    public function testIdIsThrowsInvalidArgumentExceptionWhenDatatypeIsNotValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::isId(1, 'foo');
    }

    public function testIsIdReturnsFalseWhenNumberIsNull(): void
    {
        $this->assertFalse(Num::isId(null));
    }

    /**
     * @dataProvider provideNonNumericValues
     */
    public function testIsIdReturnsFalseWhenNumberIsNaN($value): void
    {
        $this->assertFalse(Num::isId($value));
    }

    public function testIsIdReturnsFalseWhenNumberIsZero(): void
    {
        $this->assertFalse(Num::isId(0));
    }

    public function testIsIdReturnsFalseWhenNumberIsFloat(): void
    {
        $this->assertFalse(Num::isId(1.2));
    }

    public function testIsIdReturnsFalseWhenNumberIsGreaterThanDatatypeMax(): void
    {
        $this->assertFalse(Num::isId(999, 'tiny'));
    }

    public function testIsIdReturnsTrueWhenNumberIsId(): void
    {
        $this->assertTrue(Num::isId(1, 'tiny'));
    }

    public function testIsIntReturnsFalseWhenNumberIsNull(): void
    {
        $this->assertFalse(Num::isInt(null));
    }

    /**
     * @dataProvider provideNonNumericValues
     */
    public function testIsIntReturnsFalseWhenNumberIsNaN($number): void
    {
        $this->assertFalse(Num::isInt($number));
    }

    /**
     * @dataProvider provideIntegerValues
     */
    public function testIsIntReturnsTrueWhenNumberIsInteger($number): void
    {
        $this->assertTrue(Num::isInt($number));
    }

    /**
     * @dataProvider provideFloatValues
     */
    public function testIsIntReturnsFalseWhenNumberIsFloat($number): void
    {
        $this->assertFalse(Num::isInt($number));
    }

    public function testIsNumericReturnsFalseWhenNumberIsNull(): void
    {
        $this->assertFalse(Num::isNumeric(null));
    }

    /**
     * @dataProvider  provideNonNumericValues
     */
    public function testIsNumericReturnsFalseWhenNumberIsNaN($number): void
    {
        $this->assertFalse(Num::isNumeric($number));
    }

    /**
     * @dataProvider provideIntegerValues
     */
    public function testIsNumericReturnsTrueWhenNumberIsFloat($number): void
    {
        $this->assertTrue(Num::isNumeric($number));
    }

    /**
     * @dataProvider  provideIntegerValues
     */
    public function testIsNumericReturnsTrueWhenNumberIsInt($number): void
    {
        $this->assertTrue(Num::isNumeric($number));
    }

    public function testIsNumericReturnsTrueWhenNumberIsFraction(): void
    {
        $this->assertTrue(Num::isNumeric('1/2'));
    }

    public function testIsNumericReturnsTrueWhenNumberIsMixed(): void
    {
        $this->assertTrue(Num::isNumeric('1 1/2'));
    }

    public function testIsNumericReturnsTrueWhenNumberIsCommaSeparated(): void
    {
        $this->assertTrue(Num::isNumeric('1,000'));
    }

    public function testIsZeroReturnsFalseWhenNumberIsNull(): void
    {
        $this->assertFalse(Num::isZero(null));
    }

    /**
     * @dataProvider  provideNonNumericValues
     */
    public function testIsZeroReturnsFalseWhenNumberIsNaN($number): void
    {
        $this->assertFalse(Num::isZero($number));
    }

    public function testIsZeroReturnsFalseWhenNumberIsNotZero(): void
    {
        $this->assertFalse(Num::isZero(1));
    }

    /**
     * @dataProvider  provideZeroValues
     */
    public function testIsZeroReturnsTrueWhenNumberIsIntZero($number): void
    {
        $this->assertTrue(Num::isZero(0));
    }

    public function testNormalizeThrowsInvalidArgumentExceptionWhenNumberIsNaN(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::normalize('foo');
    }

    public function testNormalizeThrowsInvalidArgumentExceptionWhenMaxIsNaN(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::normalize(1, 'foo');
    }

    public function testNormalizeThrowsInvalidArgumentExceptionWhenMaxIsNegative(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::normalize(1, -1);
    }

    public function testNormalizeThrowsInvalidArgumentExceptionWhenMaxIsZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::normalize(1, 0);
    }

    public function testNormalizeReturnsZeroWhenNumberIsLessThanZero(): void
    {
        $this->assertEquals(0, Num::normalize(-1, 1));
    }

    public function testNormalizeReturnsNormalizationWhenNumberIsLessThanMax(): void
    {
        $this->assertEquals(0.5, Num::normalize(5, 10));
    }

    public function testNormalizeThrowsInvalidArgumentExceptionWhenNumberIsGreaterThanMax(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::normalize(10, 1);
    }

    public function testRoundToThrowsInvalidArgumentExceptionWhenNumberIsNaN(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::roundTo('foo');
    }

    public function testRoundToThrowsInvalidArgumentExceptionWhenMultipleIsNaN(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::roundTo(1, 'foo');
    }

    public function testRoundToThrowsInvalidArgumentExceptionWhenMultipleIsNegative(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::roundTo(1, -1);
    }

    public function testRoundToThrowsInvalidArgumentExceptionWhenMultipleIsZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Num::roundTo(1, 0);
    }

    public function testRoundToReturnsPhpNativeRoundWhenMultipleIsOmitted(): void
    {
        $this->assertEquals(round(1.5), Num::roundTo(1.5));
    }

    public function testRoundToReturnsRoundWhenNumberAndMultipleAreInts(): void
    {
        $this->assertEquals(2, Num::roundTo(1.5, 2));
    }

    public function testRoundToReturnsRoundWhenNumberAndMultipleAreFloats(): void
    {
        $this->assertEquals(1.5, Num::roundTo(1.7, 1.5));
    }

    public function testRoundToReturnsRoundWhenNumberIsIntAndMultipleIsFloat(): void
    {
        $this->assertEquals(1.5, Num::roundTo(2, 1.5));
    }

    public function testRoundToReturnsRoundWhenNumberIsFloatAndMultipleIsInt(): void
    {
        $this->assertEquals(2, Num::roundTo(1.1, 2));
    }

    public function testValReturnsIntegerWhenVarIsBool(): void
    {
        $this->assertEquals(1, Num::val(true));
    }

    public function testValReturnsFloatWhenVarIsAFloat(): void
    {
        $this->assertEquals(1.2, Num::val(1.2));
    }

    public function testValReturnsIntWhenVarIsAnInt(): void
    {
        $this->assertEquals(1, Num::val(1));
    }

    public function testValReturnsFloatWhenVarIsAStringFloat(): void
    {
        $this->assertEquals(1.0, Num::val('1.0'));
    }

    public function testValReturnsIntegerWhenVarIsStringInteger(): void
    {
        $this->assertEquals(1, Num::val('1'));
    }

    /**
     * @group  foo
     */
    public function testValReturnsFloatWhenVarIsAStringFraction(): void
    {
        $this->assertEquals(0.5, Num::val('1/2'));
    }

    public function testValReturnsFloatWhenVarIsAStringMixedNumber(): void
    {
        $this->assertEquals(1.5, Num::val('1 1/2'));
    }

    public function testValReturnsIntegerWhenVarIsAStringCommaSeparatedInteger(): void
    {
        $this->assertEquals(1000, Num::val('1,000'));
    }

    public function testValReturnsFloatWhenVarIsAStringCommaSeparatedFloat(): void
    {
        $this->assertEquals(1000.5, Num::val('1,000.5'));
    }

    public function testValReturnsZeroWhenVarIsANonNumericString(): void
    {
        $this->assertEquals(0, Num::val('foo'));
    }

    public function testValReturnsZeroWhenVarIsAnEmptyArray(): void
    {
        $this->assertEquals(0, Num::val([]));
    }

    public function testValReturnsOneWhenVarIsANonEmptyArray(): void
    {
        $this->assertEquals(1, Num::val([1, 2, 3]));
    }

    public function testValReturnsOneWhenVarIsAnObject(): void
    {
        $this->assertEquals(1, Num::val(new \StdClass()));
    }

    public function testValReturnsIntWhenVarIsACardinalString(): void
    {
        $this->assertEquals(1, Num::val('one'));
    }

    public function testValReturnsIntWhenVarIsAnOrdinalString(): void
    {
        $this->assertEquals(1, Num::val('first'));
    }

    public function testValReturnsIntWhenVarIsShortName(): void
    {
        $this->assertEquals(222, Num::val('two hundred and twenty-two'));
    }

    public function testValReturnsIntWhenVarIsMediumName(): void
    {
        $this->assertEquals(
            111111,
            Num::val('one hundred eleven thousand, one hundred and eleven')
        );
    }

    public function testValReturnsIntWhenVarIsLongName(): void
    {
        $this->assertEquals(
            1437522,
            Num::val('one million four hundred thirty-seven thousand five hundred twenty-two')
        );
    }
}
