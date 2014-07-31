<?php
/**
 * A class to test Bool objects
 *
 * @author     Jack
 * @license    MIT License <http://opensource.org/licenses/MIT>
 * @package    Jstewmc/PhpHelpers <https://github.com/jstewmc/php-helpers>
 * @since      July 2014
 *
 */

namespace Jstewmc/PhpHelpers;

class BoolTest extends PHPUnit_Framework_TestCase
{
	/**
	 * A test for the booltostr() method
	 *
	 * @access  public
	 * @return  void
	 *
	 */
	public function testBooltostr()
	{
		$this->assertEquals(Bool::booltostr(true), 'true');
		$this->assertEquals(Bool::booltostr(false), 'false');
		
		$this->assertEquals(Bool::booltostr(true, 'tf'), 'true');
		$this->assertEquals(Bool::booltostr(true, 't/f'), 'true');
		$this->assertEquals(Bool::booltostr(true, 't-f'), 'true');
		$this->assertEquals(Bool::booltostr(true, 'true/false'), 'true');
		$this->assertEquals(Bool::booltostr(true, 'true-false'), 'true');
		$this->assertEquals(Bool::booltostr(false, 'tf'), 'false');
		$this->assertEquals(Bool::booltostr(false, 't/f'), 'false');
		$this->assertEquals(Bool::booltostr(false, 't-f'), 'false');
		$this->assertEquals(Bool::booltostr(false, 'true/false'), 'false');
		$this->assertEquals(Bool::booltostr(false, 'true-false'), 'false');
		
		$this->assertEquals(Bool::booltostr(true, 'yn'), 'yes');
		$this->assertEquals(Bool::booltostr(true, 'y/n'), 'yes');
		$this->assertEquals(Bool::booltostr(true, 'y-n'), 'yes');
		$this->assertEquals(Bool::booltostr(true, 'yes/no'), 'yes');
		$this->assertEquals(Bool::booltostr(true, 'yes-no'), 'yes');
		$this->assertEquals(Bool::booltostr(false, 'yn'), 'no');
		$this->assertEquals(Bool::booltostr(false, 'y/n'), 'no');
		$this->assertEquals(Bool::booltostr(false, 'y-n'), 'no');
		$this->assertEquals(Bool::booltostr(false, 'yes/no'), 'no');
		$this->assertEquals(Bool::booltostr(false, 'yes-no'), 'no');
		
		$this->assertEquals(Bool::booltostr(true, 'oo'), 'on');
		$this->assertEquals(Bool::booltostr(true, 'o/o'), 'on');
		$this->assertEquals(Bool::booltostr(true, 'o-o'), 'on');
		$this->assertEquals(Bool::booltostr(true, 'on/off'), 'on');
		$this->assertEquals(Bool::booltostr(true, 'on-off'), 'on');
		$this->assertEquals(Bool::booltostr(false, 'oo'), 'off');
		$this->assertEquals(Bool::booltostr(false, 'o/o'), 'off');
		$this->assertEquals(Bool::booltostr(false, 'o-o'), 'off');
		$this->assertEquals(Bool::booltostr(false, 'on/off'), 'off');
		$this->assertEquals(Bool::booltostr(false, 'on-off'), 'off');
		
		return;
	}
	
	/**
	 * A test for the val() method
	 *
	 * @access  public
	 * @return  void
	 *
	 */
	public function testVal()
	{
		$this->assertFalse(Bool::val());
		$this->assertFalse(Bool::val(''));
		$this->assertFalse(Bool::val(0));
		$this->assertFalse(Bool::val(0.0));
		$this->assertFalse(Bool::val([]));
		$this->assertFalse(Bool::val('0'));
		$this->assertFalse(Bool::val('0.0'));
		$this->assertFalse(Bool::val('false'));
		$this->assertFalse(Bool::val('off'));
		$this->assertFalse(Bool::val('OFF'));
		$this->assertFalse(Bool::val('No'));
		
		$this->assertTrue(Bool::val(' '));
		$this->assertTrue(Bool::val('abc'));
		$this->assertTrue(Bool::val('true'));
		$this->assertTrue(Bool::val('yes'));
		$this->assertTrue(Bool::val('YES'));
		$this->assertTrue(Bool::val('on'));
		$this->assertTrue(Bool::val(1));
		$this->assertTrue(Bool::val(0.1));
		$this->assertTrue(Bool::val(['foo', 'bar', 'baz']));
		$this->assertTrue(Bool::val(['foo' => null]));
		$this->assertTrue(Bool::val(['foo' => 'bar']));
		$this->assertTrue(Bool::val(new StdClass()));
		
		return;
	}
}
