<?php
/**
 * The file for the Bool class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2014 Jack Clayton
 * @license    MIT License <http://opensource.org/licenses/MIT>
 * @package    Jstewmc/PhpHelpers <https://github.com/jstewmc/php-helpers>
 */

namespace Jstewmc\PhpHelpers;

/**
 * A class to test the Bool class
 */
class BoolTest extends \PHPUnit_Framework_TestCase
{
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
			array(new \StdClass())
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
			array(new \StdClass())
		);
	}

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
		Bool::booltostr($param);
		
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
		return $this->assertTrue(Bool::booltostr(true), 'true');
	}
	
	/**
	 * Tests whether or not booltostr() returns (string) 'false' on (bool) false
	 */
	public function testBooltostr_returnsStringFalse_onBoolFalse()
	{
		return $this->assertTrue(Bool::booltostr(false), 'false');
	}
}
