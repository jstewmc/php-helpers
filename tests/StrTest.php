<?php

namespace Jstewmc\PhpHelpers;

class StrTest extends \PHPUnit\Framework\TestCase
{
    public function testEndsWithReturnsFalseWhenHaystackIsEmpty(): void
    {
        $this->assertFalse(Str::endsWith('', 'foo'));
    }

    public function testEndsWithReturnsFalseWhenNeedleIsEmpty(): void
    {
        $this->assertFalse(Str::endsWith('foo', ''));
    }

    public function testEndsWithReturnsFalseWhenHaystackDoesNotEndWithNeedle(): void
    {
        $this->assertFalse(Str::endsWith('foobar', 'baz'));
    }

    public function testEndsWithReturnsTrueWhenHaystackEndsWithNeedle(): void
    {
        $this->assertTrue(Str::endsWith('foobar', 'bar'));
    }

    public function testEndsWithReturnsFalseWhenHaystackEndsWithNeedleCaseMismatch(): void
    {
        $this->assertFalse(Str::endsWith('foobar', 'BAR'));
    }

    public function testIEndsWithReturnsFalseWhenHaystackIsEmpty(): void
    {
        $this->assertFalse(Str::iEndsWith('', 'foo'));
    }

    public function testIEndsWithReturnsFalseWhenNeedleIsEmpty(): void
    {
        $this->assertFalse(Str::iEndsWith('foo', ''));
    }

    public function testIEndsWithReturnsTrueWhenHaystackEndsWithNeedleCaseMatch(): void
    {
        $this->assertTrue(Str::iEndsWith('foobar', 'bar'));
    }

    public function testIEndsWithReturnsTrueWhenHaystackEndsWithNeedleCaseMismatch(): void
    {
        $this->assertTrue(Str::iEndsWith('foobar', 'BAR'));
    }

    public function testIEndsWithReturnsFalseWhenHaystackDoesNotEndWithNeedle(): void
    {
        $this->assertFalse(Str::iEndsWith('foobar', 'baz'));
    }

    public function testIsBoolReturnsFalseWhenValueIsNotString(): void
    {
        $this->assertFalse(Str::isBool(1));
    }

    public function testIsBoolReturnsFalseWhenValueIsNotValid(): void
    {
        $this->assertFalse(Str::isBool('foo'));
    }

    public function testIsBoolReturnsTrueWhenValueIsValid(): void
    {
        $this->assertTrue(Str::isBool('true'));
    }

    public function testIStartsWithReturnsFalseWhenHaystackDoesNotStartWithNeedle(): void
    {
        $this->assertFalse(Str::iStartsWith('foobar', 'baz'));
    }

    public function testIStartsWithReturnsTrueWhenHaystackStartsWithNeedleAndCaseMatches(): void
    {
        $this->assertTrue(Str::iStartsWith('foobar', 'foo'));
    }

    public function testIStartsWithReturnsTrueWhenHaystackStartsWithNeedleAndCaseMisMatch(): void
    {
        $this->assertTrue(Str::iStartsWith('foobar', 'FOO'));
    }

    public function testPasswordThrowsInvalidArgumentExceptionWhenCharsetIsInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Str::password(8, ['foo' => 1]);
    }

    public function testPasswordReturnsStringOfLengthWhenLengthIsInteger(): void
    {
        $this->assertEquals(strlen(Str::password(16)), 16);
    }

    public function testPasswordReturnStringOfRulesIfRulesAreValid(): void
    {
        $results = Str::password(12, ['lower' => 4, 'upper' => 4, 'number' => 4]);

        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9]{12}$/', $results);
    }

    public function testRandThrowsInvalidArgumentExceptionWhenCharsetsIsNeitherStringNorArray(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Str::rand(8, 1);
    }

    public function testRandThrowsInvalidArgumentExceptionWhenChartsetIsInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Str::rand(8, 'foo');
    }

    public function testRandReturnsStringOfLengthWhenLengthIsInt(): void
    {
        $this->assertEquals(strlen(Str::rand(8)), 8);
    }

    public function testRandReturnsStringOfCharsetsWhenCharsetsAreValid(): void
    {
        $this->assertMatchesRegularExpression(
            '/^[0-9A-Z]{8}$/',
            Str::rand(8, ['upper', 'number'])
        );
    }

    public function testSplitOnFristAlphaReturnsEmptyArrayWhenStringIsEmpty(): void
    {
        $result = Str::splitOnFirstAlpha('');

        $this->assertTrue(is_array($result));
        $this->assertEquals(count($result), 0);
    }

    public function testSplitOnFirstAlphaReturnsArrayWithOneElementIfStringStartsWithAlpha(): void
    {
        $input  = 'foo';
        $result = Str::splitOnFirstAlpha($input);

        $this->assertTrue(is_array($result));
        $this->assertEquals($result[1], $input);
    }

    public function testSplitOnFirstAlphaReturnsArrayWithOneElementWhenStringDoesNotContainAlpha(): void
    {
        $input  = '123';
        $result = Str::splitOnFirstAlpha($input);

        $this->assertTrue(is_array($result));
        $this->assertEquals($result[0], $input);
    }

    public function testSplitOnFirstAlphaReturnsArrayWithTwoElementsWhenStringContainsAlpha(): void
    {
        $input  = '123 foo';
        $result = Str::splitOnFirstAlpha($input);

        $this->assertTrue(is_array($result));
        $this->assertEquals($result[0], '123');
        $this->assertEquals($result[1], 'foo');
    }

    public function testStartsWithReturnsFalseWhenHaystackDoesNotStartWithNeedle(): void
    {
        $this->assertFalse(Str::startsWith('foobar', 'baz'));
    }

    public function testStartsWithReturnsTrueWhenHaystackStartsWithNeedleAndCaseMatches(): void
    {
        $this->assertTrue(Str::startsWith('foobar', 'foo'));
    }

    public function testStartsWithReturnsFalseIfHaystackStartsWithNeedleAndCaseMisMatch(): void
    {
        $this->assertFalse(Str::startsWith('foobar', 'FOO'));
    }

    public function testStrtobytesThrowsInvalidArgumentExceptionWhenStringIsNotValid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Str::strtobytes('foo');
    }

    public function testStrtobytesReturnsIntegerWhenStringIsValid(): void
    {
        $this->assertEquals(Str::strtobytes('1M'), 1024 * 1024);
    }

    public function testStrtocamelcaseReturnEmptyStringWhenStringIsEmpty(): void
    {
        $this->assertEquals(Str::strtocamelcase(''), '');
    }

    public function testStrtocamelcaseReturnsCamelCaseStringWhenStringIsMixedCase(): void
    {
        $this->assertEquals(Str::strtocamelcase('foo BAR'), 'fooBar');
    }

    public function testStrtocamelcaseReturnsCamelCaseStringWhenStringContainsSymbols(): void
    {
        $this->assertEquals(Str::strtocamelcase(';foo *()_bar!'), 'fooBar');
    }

    public function testStrtocamelcaseReturnsCamelCaseStringWhenStringContainsNumbers(): void
    {
        $this->assertEquals(Str::strtocamelcase('f00 b3r'), 'f00B3r');
    }

    public function testTruncateReturnsStringWhenStringShorterThanLimit(): void
    {
        $this->assertEquals(Str::truncate('foo', 8), 'foo');
    }

    public function testTruncateReturnsStringTruncatedAtBreakWhenStringIsLongerThanLimit(): void
    {
        $this->assertEquals(Str::truncate('foo bar', 5, ' ', '...'), 'foo...');
    }

    public function testTruncateReturnsStringTruncatedExactWhenBreakIsEmptyString(): void
    {
        $this->assertEquals(Str::truncate('foo bar', 5, null), 'foo b...');
    }
}
