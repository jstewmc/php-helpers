<?php
/**
 * The file for the StrTest class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2014 Jack Clayton
 * @license    MIT License <http://opensource.org/licenses/MIT>
 * @package    Jstewmc/PhpHelpers <https://github.com/jstewmc/php-helpers>
 */

use Jstewmc\PhpHelpers\Str;

/**
 * A class to test the Str class
 */
class StrTest extends \PHPUnit\Framework\TestCase
{
	/* !Providers */

	/**
	 * Provides an array of non-array values
	 */
	public function provideNonArrayValues()
	{
		return array(
			array(true),
			array(1),
			array(1.0),
			array('foo'),
			array(new StdClass())
		);
	}

	/**
	 * Provides non-integer values
	 */
	public function provideNonIntegerValues()
	{
		return array(
			array(true),
			array(1.0),
			array('foo'),
			array(array()),
			array(new StdClass())
		);
	}

	/**
	 * Provides non-string values
	 */
	public function provideNonStringValues()
	{
		return array(
			array(true),
			array(1),
			array(1.0),
			array(array()),
			array(new StdClass())
		);
	}

	/**
	 * Provides non-string and non-array values
	 */
	public function provideNeitherStringNorArrayValues()
	{
		return array(
			array(true),
			array(1),
			array(1.0),
			array(new StdClass())
		);
	}


	/* !endsWith() */

	/**
	 * endsWith() should throw a BadMethodCallException if needle and haystack are null
	 */
	public function testEndsWith_throwsBadMethodCallException_ifNeedleAndHaystackAreNull()
	{
		$this->expectException('BadMethodCallException');
		Str::endsWith(null, null);

		return;
	}

	/**
	 * endsWith() should throw an InvalidArgumentException if haystack is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testEndsWith_throwsInvalidArgumentException_ifHaystackIsNotAString($haystack)
	{
		$this->expectException('InvalidArgumentException');
		Str::endsWith($haystack, 'foo');

		return;
	}

	/**
	 * endsWith() should throw an InvalidArgumentException if needle is not a string
	 *
	 * @dataProvider provideNonstringValues
	 */
	public function testEndsWith_throwsInvalidArgumentException_ifNeedleIsNotAString($needle)
	{
		$this->expectException('InvalidArgumentException');
		Str::endsWith('foo', $needle);

		return;
	}

	/**
	 * endsWith() should return false if haystack is empty
	 */
	public function testEndsWith_returnsFalse_ifHaystackIsEmpty()
	{
		return $this->assertFalse(Str::endsWith('', 'foo'));
	}

	/**
	 * endsWith() should return false if needle is empty
	 */
	public function testEndsWith_returnsFalse_ifNeedleIsEmpty()
	{
		return $this->assertFalse(Str::endsWith('foo', ''));
	}

	/**
	 * endsWith() should return true if the haystack ends with needle and case matches
	 */
	public function testEndsWith_returnsTrue_ifHaystackEndsWithNeedleCaseMatch()
	{
		return $this->assertTrue(Str::endsWith('foobar', 'bar'));
	}

	/**
	 * endsWith() should return false if the haystack ends with needle and case
	 *     does not match
	 */
	public function testEndsWith_returnsFalse_ifHaystackEndsWithNeedleCaseMismatch()
	{
		return $this->assertFalse(Str::endsWith('foobar', 'BAR'));
	}

	/**
	 * endsWith() should return false if the haystack does not end with needle
	 */
	public function testEndsWith_returnsFalse_ifHaystackDoesNotEndWithNeedle()
	{
		return $this->assertFalse(Str::endsWith('foobar', 'baz'));
	}


	/* !iEndsWith() */

	/**
	 * iEndsWith() should throw a BadMethodCallException if needle and haystack are null
	 */
	public function testIEndsWith_throwsBadMethodCallException_ifNeedleAndHaystackAreNull()
	{
		$this->expectException('BadMethodCallException');
		Str::iEndsWith(null, null);

		return;
	}

	/**
	 * iEndsWith() should throw an InvalidArgumentException if haystack is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testIEndsWith_throwsInvalidArgumentException_ifHaystackIsNotAString($haystack)
	{
		$this->expectException('InvalidArgumentException');
		Str::iEndsWith($haystack, 'foo');

		return;
	}

	/**
	 * iEndsWith() should throw an InvalidArgumentException if needle is not a string
	 *
	 * @dataProvider provideNonstringValues
	 */
	public function testIEndsWith_throwsInvalidArgumentException_ifNeedleIsNotAString($needle)
	{
		$this->expectException('InvalidArgumentException');
		Str::iEndsWith('foo', $needle);

		return;
	}

	/**
	 * iEndsWith() should return false if haystack is empty
	 */
	public function testIEndsWith_returnsFalse_ifHaystackIsEmpty()
	{
		return $this->assertFalse(Str::iEndsWith('', 'foo'));
	}

	/**
	 * iEndsWith() should return false if needle is empty
	 */
	public function testIEndsWith_returnsFalse_ifNeedleIsEmpty()
	{
		return $this->assertFalse(Str::iEndsWith('foo', ''));
	}

	/**
	 * iEndsWith() should return true if the haystack ends with needle and case matches
	 */
	public function testIEndsWith_returnsTrue_ifHaystackEndsWithNeedleCaseMatch()
	{
		return $this->assertTrue(Str::iEndsWith('foobar', 'bar'));
	}

	/**
	 * iEndsWith() should return true if the haystack ends with needle and case
	 *     does not match
	 */
	public function testIEndsWith_returnsTrue_ifHaystackEndsWithNeedleCaseMismatch()
	{
		return $this->assertTrue(Str::iEndsWith('foobar', 'BAR'));
	}

	/**
	 * iEndsWith() should return false if the haystack does not end with needle
	 */
	public function testIEndsWith_returnsFalse_ifHaystackDoesNotEndWithNeedle()
	{
		return $this->assertFalse(Str::iEndsWith('foobar', 'baz'));
	}


	/* !isBool() */

	/**
	 * isBool() should return false if the value is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testIsBool_returnsFalse_ifValueIsNotString($value)
	{
		return $this->assertFalse(Str::isBool($value));
	}

	/**
	 * isBool() should return false if the value is not a bool string
	 */
	public function testIsBool_returnsFalse_ifValueIsNotValid()
	{
		return $this->assertFalse(Str::isBool('foo'));
	}

	/**
	 * isBool() should return true if the string is a bool string
	 */
	public function testIsBool_returnsTrue_ifValueIsValid()
	{
		return $this->assertTrue(Str::isBool('true'));
	}


	/* !iStartsWith() */

	/**
	 * iStartsWith() should throw a BadMethodCallException if haystack and needle
	 *     are null
	 */
	public function testIStartsWith_throwsBadMethodCallException_ifHaystackAndNeedleAreNull()
	{
		$this->expectException('BadMethodCallException');
		Str::iStartsWith(null, null);

		return;
	}

	/**
	 * iStartsWith() should throw an InvalidArgumentException if haystack is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testIStartsWith_throwsInvalidArgumentException_ifHaystackIsNotAString($haystack)
	{
		$this->expectException('InvalidArgumentException');
		Str::iStartsWith($haystack, 'foo');

		return;
	}

	/**
	 * iStartsWith() should throw an InvalidArgumentException if needle is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testIStartsWith_throwsInvalidArgumentException_ifNeedleIsNotAString($needle)
	{
		$this->expectException('InvalidArgumentException');
		Str::iStartsWith('foo', $needle);

		return;
	}

	/**
	 * iStartsWith() should return false if the haystack does not start with needle
	 */
	public function testIStartsWith_returnsFalse_ifHaystackDoesNotStartWithNeedle()
	{
		return $this->assertFalse(Str::iStartsWith('foobar', 'baz'));
	}

	/**
	 * iStartsWith() should return true if the haystack starts with needle and the
	 *     case matches
	 */
	public function testIStartsWith_returnsTrue_ifHaystackStartsWithNeedleAndCaseMatches()
	{
		return $this->assertTrue(Str::iStartsWith('foobar', 'foo'));
	}

	/**
	 * iStartsWith() should return true if the haystack starts with needle and the
	 *     case does not match
	 */
	public function testIStartsWith_returnsTrue_ifHaystackStartsWithNeedleAndCaseMisMatch()
	{
		return $this->assertTrue(Str::iStartsWith('foobar', 'FOO'));
	}


	/* !password() */

	/**
	 * password() should throw a BadMethodCallException if rules and length are null
	 */
	public function testPassword_throwsBadMethodCallException_ifNullArguments()
	{
		$this->expectException('BadMethodCallException');
		Str::password(null, null);

		return;
	}

	/**
	 * password() should throw an InvalidArgumentException if length is not an integer
	 *
	 * @dataProvider provideNonIntegerValues
	 */
	public function testPassword_throwsInvalidArgumentException_ifLengthIsNotInteger($length)
	{
		$this->expectException('InvalidArgumentException');
		Str::password($length, array('lower' => 1));

		return;
	}

	/**
	 * password() should throw an InvalidArgumentException if rules is not an array
	 *
	 * @dataProvider provideNonArrayValues
	 */
	public function testPassword_throwsInvalidArgumentException_ifRulesIsNotArray($rules)
	{
		$this->expectException('InvalidArgumentException');
		Str::password(8, $rules);

		return;
	}

	/**
	 * password() should throw an InvalidArgumentException if charset in rules is invalid
	 */
	public function testPassword_throwsInvalidArgumentException_ifCharsetIsInvalid()
	{
		$this->expectException('InvalidArgumentException');
		Str::password(8, array('foo' => 1));

		return;
	}

	/**
	 * password() should return string of length if length is a positive integer
	 */
	public function testPassword_returnsStringOfLength_ifLengthIsInteger()
	{
		return $this->assertEquals(strlen(Str::password(16)), 16);
	}

	/**
	 * password() should return string that follows the rules if rules are valid
	 */
	public function testPassword_returnStringOfRules_ifRulesAreValid()
	{
		$results = Str::password(12, array('lower' => 4, 'upper' => 4, 'number' => 4));

		$this->assertMatchesRegularExpression('/^[a-zA-Z0-9]{12}$/', $results);

		return;
	}


	/* !rand() */

	/**
	 * rand() should throw a BadMethodCallException if length or charset is null
	 */
	public function testRand_throwsBadMethodCallException_ifNullArguments()
	{
		$this->expectException('BadMethodCallException');
		Str::rand(null, null);

		return;
	}

	/**
	 * rand() should throw an InvalidArgumentException if length is not an integer
	 *
	 * @dataProvider provideNonIntegerValues
	 */
	public function testRand_throwsInvalidArgumentException_ifLengthIsNaN($length)
	{
		$this->expectException('InvalidArgumentException');
		Str::rand($length);

		return;
	}

	/**
	 * rand() should throw InvalidArgumentException if charsets is not a string
	 *     or array
	 *
	 * @dataProvider provideNeitherStringNorArrayValues
	 */
	public function testRand_throwsInvalidArgumentException_ifCharsetsIsNotStringOrArray($charsets)
	{
		$this->expectException('InvalidArgumentException');
		Str::rand(8, $charsets);

		return;
	}

	/**
	 * rand() should throw an InvalidArgumentException if charset name is invalid
	 */
	public function testRand_throwsInvalidArgumentException_ifChartsetIsInvalid()
	{
		$this->expectException('InvalidArgumentException');
		Str::rand(8, 'foo');

		return;
	}

	/**
	 * rand() should return a string of the given length
	 */
	public function testRand_returnsStringOfLength_ifLengthIsInt()
	{
		return $this->assertEquals(strlen(Str::rand(8)), 8);
	}

	/**
	 * rand() should return a string of the given charsets
	 */
	public function testRand_returnsStringOfCharsets_ifCharsetsAreValid()
	{
		return $this->assertMatchesRegularExpression(
			'/^[0-9A-Z]{8}$/',
			Str::rand(8, array('upper', 'number'))
		);
	}


	/* !splitOnFirstAlpha() */

	/**
	 * splitOnFirstAlpha() should throw a BadMethodCallException if string is null
	 */
	public function testSplitOnFirstAlpha_throwsBadMethodCallException_ifStringIsNull()
	{
		$this->expectException('BadMethodCallException');
		Str::splitOnFirstAlpha(null);

		return;
	}

	/**
	 * splitOnFirstAlpha() should throw InvalidArgumentException if string is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testSplitOnFirstAlpha_throwsInvalidArgumentException_ifStringIsNotAString($string)
	{
		$this->expectException('InvalidArgumentException');
		Str::splitOnFirstAlpha($string);

		return;
	}

	/**
	 * splitOnFirstAlpha() should return an empty array if string is empty
	 */
	public function testSplitOnFristAlpha_returnEmptyArray_ifStringEmpty()
	{
		$result = Str::splitOnFirstAlpha('');

		$this->assertTrue(is_array($result));
		$this->assertEquals(count($result), 0);

		return;
	}

	/**
	 * splitOnFirstAlpha() should return an array with one element if the string
	 *     starts with letter
	 */
	public function testSplitOnFirstAlpha_returnArrayWithOneElement_ifStringStartsWithAlpha()
	{
		$input  = 'foo';
		$result = Str::splitOnFirstAlpha($input);

		$this->assertTrue(is_array($result));
		$this->assertEquals($result[1], $input);

		return;
	}

	/**
	 * splitOnFirstAlpha() should return an array with one element if the string
	 *     does not contain a letter
	 */
	public function testSplitOnFirstAlpha_returnArrayWithOneElement_ifStringDoesNotContainAlpha()
	{
		$input  = '123';
		$result = Str::splitOnFirstAlpha($input);

		$this->assertTrue(is_array($result));
		$this->assertEquals($result[0], $input);

		return;
	}

	/**
	 * splitOnFirstAlpha() should return an array with two elements if the string
	 *     contains a letter
	 */
	public function testSplitOnFirstAlpha_returnArrayWithTwoElements_ifStringContainsAlpha()
	{
		$input  = '123 foo';
		$result = Str::splitOnFirstAlpha($input);

		$this->assertTrue(is_array($result));
		$this->assertEquals($result[0], '123');
		$this->assertEquals($result[1], 'foo');

		return;
	}


	/* !startsWith() */

	/**
	 * startsWith() should throw a BadMethodCallException if haystack and needle
	 *     are null
	 */
	public function testStartsWith_throwsBadMethodCallException_ifHaystackAndNeedleAreNull()
	{
		$this->expectException('BadMethodCallException');
		Str::startsWith(null, null);

		return;
	}

	/**
	 * startsWith() should throw an InvalidArgumentException if haystack is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testStartsWith_throwsInvalidArgumentException_ifHaystackIsNotAString($haystack)
	{
		$this->expectException('InvalidArgumentException');
		Str::startsWith($haystack, 'foo');

		return;
	}

	/**
	 * startsWith() should throw an InvalidArgumentException if needle is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testStartsWith_throwsInvalidArgumentException_ifNeedleIsNotAString($needle)
	{
		$this->expectException('InvalidArgumentException');
		Str::startsWith('foo', $needle);

		return;
	}

	/**
	 * startsWith() should return false if the haystack does not start with needle
	 */
	public function testStartsWith_returnsFalse_ifHaystackDoesNotStartWithNeedle()
	{
		return $this->assertFalse(Str::startsWith('foobar', 'baz'));
	}

	/**
	 * startsWith() should return true if the haystack starts with needle and the
	 *     case matches
	 */
	public function testStartsWith_returnsTrue_ifHaystackStartsWithNeedleAndCaseMatches()
	{
		return $this->assertTrue(Str::startsWith('foobar', 'foo'));
	}

	/**
	 * startsWith() should return false if the haystack starts with needle and the
	 *     case does not match
	 */
	public function testStartsWith_returnsFalse_ifHaystackStartsWithNeedleAndCaseMisMatch()
	{
		return $this->assertFalse(Str::startsWith('foobar', 'FOO'));
	}


	/* !strtobytes() */

	/**
	 * strtobytes() should throw a BadMethodCallException if string is null
	 */
	public function testStrtobytes_throwsBadMethodCallException_ifStringIsNull()
	{
		$this->expectException('BadMethodCallException');
		Str::strtobytes(null);

		return;
	}

	/**
	 * strtobytes() should throw an InvalidArgumentException if string is not actually
	 *     a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testStrtobytes_throwsInvalidArgumentException_ifStringIsNotAString($string)
	{
		$this->expectException('InvalidArgumentException');
		Str::strtobytes($string);

		return;
	}

	/**
	 * strtobytes() should throw an InvalidArgumentException if string does not end with
	 *     'k', 'm', or 'g'
	 */
	public function testStrtobytes_throwsInvalidArgumentException_ifStringIsNotValue()
	{
		$this->expectException('InvalidArgumentException');
		Str::strtobytes('foo');

		return;
	}

	/**
	 * strtobytes() should return an integer value is string is valid
	 */
	public function testStrtobytes_returnsInteger_ifStringIsValid()
	{
		return $this->assertEquals(Str::strtobytes('1M'), 1024 * 1024);
	}


	/* !strtocamelcase() */

	/**
	 * strtocamelcase() should throw a BadMethodCallException if string is null
	 */
	public function testStrtocamelcase_throwsBadMethodCallException_ifStringIsNull()
	{
		$this->expectException('BadMethodCallException');
		Str::strtocamelcase(null);

		return;
	}

	/**
	 * strtocamelcase() should throw an InvalidArgumentException if string is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testStrtocamelcase_throwsInvalidArgumentException_ifStringIsNotString($value)
	{
		$this->expectException('InvalidArgumentException');
		Str::strtocamelcase($value);

		return;
	}

	/**
	 * strtocamelcase() should return an empty string if string is empty
	 */
	public function testStrtocamelcase_returnEmpty_ifStringIsEmpty()
	{
		return $this->assertEquals(Str::strtocamelcase(''), '');
	}

	/**
	 * strtocamelcase() returns camel-cased string if string is mixed case
	 */
	public function testStrtocamelcase_returnCamelCaseString_ifStringIsMixedCase()
	{
		return $this->assertEquals(
			Str::strtocamelcase('hello WORLD'),
			'helloWorld'
		);
	}

	/**
	 * strtocamelcase() returns camel-cased string if string contains symbols
	 */
	public function testStrtocamelcase_returnCamelCaseString_ifStringContainsSymbols()
	{
		return $this->assertEquals(
			Str::strtocamelcase(';hello *()_world!'),
			'helloWorld'
		);
	}

	/**
	 * strtocamelcase() returns camel-cased string if string contains numbers
	 */
	public function testStrtocamelcase_returnCamelCaseString_ifStringContainsNumbers()
	{
		return $this->assertEquals(Str::strtocamelcase('h3llo w0rld'), 'h3lloW0rld');
	}


	/* !truncate() */

	/**
	 * truncate() should throw a BadMethodCallException if string and limit are null
	 */
	public function testTruncate_throwsBadMethodCallException_ifNullArguments()
	{
		$this->expectException('BadMethodCallException');
		Str::truncate(null, null);

		return;
	}

	/**
	 * truncate() should throw an InvalidArgumentException if string is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testTruncate_throwsInvalidArgumentException_ifStringIsNotAString($string)
	{
		$this->expectException('InvalidArgumentException');
		Str::truncate($string, 8);

		return;
	}

	/**
	 * truncate() should throw an InvalidArgumentException if limit is not a number
	 *
	 * @dataProvider provideNonIntegerValues
	 */
	public function testTruncate_throwsInvalidArgumentException_ifLimitIsNaN($limit)
	{
		$this->expectException('InvalidArgumentException');
		Str::truncate('foo', $limit);

		return;
	}

	/**
	 * truncate() should throw an InvalidArgumentException if break is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testTruncate_throwsInvalidArgumentException_ifBreakIsNotAString($break)
	{
		$this->expectException('InvalidArgumentException');
		Str::truncate('foo', 8, $break);

		return;
	}

	/**
	 * truncate() should throw an InvalidArgumentException if pad is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testTruncate_throwsInvalidArgumentException_ifPadIsNotAString($pad)
	{
		$this->expectException('InvalidArgumentException');
		Str::truncate('foo', 8, ' ', $pad);

		return;
	}

	/**
	 * truncate() should return the original string if it is shorter than the limit
	 */
	public function testTruncate_returnString_ifStringShorterThanLimit()
	{
		return $this->assertEquals(Str::truncate('foo', 8), 'foo');
	}

	/**
	 * truncate() should return the string truncated at the nearest pad if the string
	 *     is longer than the limit
	 */
	public function testTruncate_returnStringTruncatedAtBreak_ifStringIsLongerThanLimit()
	{
		return $this->assertEquals(
			Str::truncate('foo bar', 5, ' ', '...'),
			'foo...'
		);
	}

	/**
	 * truncate() should return the string truncated exactly at the limit if the break
	 *     is null
	 */
	public function testTruncate_returnStringTruncatedExact_ifBreakIsEmptyString()
	{
		return $this->assertEquals(
			Str::truncate('foo bar', 5, null),
			'foo b...'
		);
	}
}
