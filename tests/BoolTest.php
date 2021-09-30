<?php
/**
 * The file for the Bool class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2014 Jack Clayton
 * @license    MIT License <http://opensource.org/licenses/MIT>
 * @package    Jstewmc/PhpHelpers <https://github.com/jstewmc/php-helpers>
 */

use Jstewmc\PhpHelpers\Bool;

/**
 * A class to test the Bool class
 */
class BoolTest extends \PHPUnit\Framework\TestCase
{
	/* !Data providers */

	/**
	 * Provides an array of values considered false by Bool::val()
	 */
	public function falseValueDataProvider()
	{
		return array(
			array(null),
			array(''),
			array('no'),
			array('off'),
			array('false'),
			array('0'),
			array('0.0'),
			array(0),
			array(0.0),
			array(array())
		);
	}

	/**
	 * Provides an array of non-bool datatypes
	 */
	public function nonBoolDataProvider()
	{
		return array(
			array(1),
			array(1.0),
			array('foo'),
			array(array()),
			array(new StdClass())
		);
	}

	/**
	 * Provides an array of non-string datatypes
	 */
	public function nonStringDataProvider()
	{
		return array(
			array(1),
			array(1.0),
			array(true),
			array(array()),
			array(new StdClass())
		);
	}

	/**
	 * Provides an array of values considered true by Bool::val()
	 */
	public function trueValueDataProvider()
	{
		return array(
			array('on'),
			array('yes'),
			array('true'),
			array('foo'),
			array('1'),
			array(-1),
			array(-1.0),
			array(1),
			array(1.0),
			array(array('foo', 'bar', 'baz')),
			array(array('foo' => null)),
			array(new StdClass())
		);
	}


	/* !bootostr() */

	/**
	 * Booltostr() should throw a BadMethodCallException on a null parameter
	 */
	public function testBooltostr_throwsBadMethodCallException_onNullParameter()
	{
		$this->setExpectedException('BadMethodCallException');
		Bool::booltostr(null);

		return;
	}

	/**
	 * Tests whether or not booltostr() throws an InvalidArgumentException on a non-bool
	 *     first parameter
	 *
	 * @dataProvider nonBoolDataProvider
	 */
	public function testBooltostr_throwsInvalidArgumentException_onNonBoolFirstParameter($param)
	{
		$this->setExpectedException('InvalidArgumentException');
		Bool::booltostr($param);

		return;
	}

	/**
	 * Tests whether or not booltostr() throws an InvalidArgumentException on a non-string
	 *     second parameter
	 *
	 * @dataProvider nonStringDataProvider
	 *
	 */
	public function testBooltostr_throwsInvalidArgumentException_onNonStringSecondParameter($param)
	{
		$this->setExpectedException('InvalidArgumentException');
		Bool::booltostr(true, $param);

		return;
	}

	/**
	 * Tests whether or not booltostr() throws an InvalidArgumentException on an invalid
	 *     second parameter
	 */
	public function testBooltostr_throwsInvalidArgumentException_onInvalidSecondParameter()
	{
		$this->setExpectedException('InvalidArgumentException');
		Bool::booltostr(true, 'foo');

		return;
	}

	/**
	 * Tests whether or not booltostr() returns (string) 'true' on (bool) true
	 */
	public function testBooltostr_returnsStringTrue_onBoolTrue()
	{
		return $this->assertEquals(Bool::booltostr(true), 'true');
	}

	/**
	 * Tests whether or not booltostr() returns (string) 'false' on (bool) false
	 */
	public function testBooltostr_returnsStringFalse_onBoolFalse()
	{
		return $this->assertEquals(Bool::booltostr(false), 'false');
	}


	/* !val() */

	/**
	 * Tests whether or not val() returns (bool) true on any string but 'no', 'off',
	 *     'false', '0', and '0.0' as well as positive numbers, negative numbers,
	 *     non-empty arrays, and objects
	 *
	 * @dataProvider trueValueDataProvider
	 */
	public function testVal_returnTrue_onTrueValue($value)
	{
		$this->assertTrue(Bool::val($value));
	}

	/**
	 * Tests whether or not val() returns (bool) false on empty strings; the strings
	 *     'no', 'off', 'false', '0', '0.0'; the numbers 0 and 0.0; and an empty array
	 *
	 * @dataProvider falseValueDataProvider
	 */
	public function testVal_returnFalse_onFalseValue($value)
	{
		$this->assertFalse(Bool::val($value));
	}
}
