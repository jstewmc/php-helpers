<?php
/**
 * A class to test the Str class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2014 Jack Clayton
 * @license    MIT License <http://opensource.org/licenses/MIT>
 * @package    Jstewmc/PhpHelpers <https://github.com/jstewmc/php-helpers>
 * @since      0.0.0
 *
 */

namespace Jstwemc/PhpHelpers;

class StrTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Tests the Str::endsWith() method
	 */
	public function testEndsWith()
	{
		$this->assertTrue(Str::endsWith('foobar', 'bar'));
		
		$this->assertFalse(Str::endsWith('foobar', 'baz'));
		$this->assertFalse(Str::endsWith('foobar', 'BAR'));
		
		return;
	}
	
	/**
	 * Tests the Str::iEndsWith() method
	 */
	public function testIEndsWith()
	{
		$this->assertTrue(Str::endsWith('foobar', 'bar'));
		$this->assertTrue(Str::endsWith('foobar', 'BAR'));

		$this->assertFalse(Str::endsWith('foobar', 'baz'));
		
		return;
	}
	
	/**
	 * Tests the Str::isBool() method
	 */
	public function testIsBool()
	{
		$this->assertTrue(Str::isBool('true'));
		$this->assertTrue(Str::isBool('false'));
		$this->assertTrue(Str::isBool('yes'));
		$this->assertTrue(Str::isBool('no'));
		$this->assertTrue(Str::isBool('on'));
		$this->assertTrue(Str::isBool('off'));
		
		$this->assertFalse(Str::isBool(''));
		$this->assertFalse(Str::isBool('foo'));
		$this->assertFalse(Str::isBool('bar'));
		$this->assertFalse(Str::isBool('baz'));
		$this->assertFalse(Str::isBool(null));
		$this->assertFalse(Str::isBool(false));
		$this->assertFalse(Str::isBool([]);
		$this->assertFalse(Str::isBool(new StdClass()))
		
		return;
	}
	
	/**
	 * Tests the Str::iStartsWith() method
	 */
	public function testIStartsWith()
	{
		$this->assertTrue(Str::iStartsWith('foobar', 'foo'));
		$this->assertTrue(Str::iStartsWith('foobar', 'FOO'));
		
		$this->assertFalse(Str::iStartsWith('foobar', 'bar'));
		$this->assertFalse(Str::iStartsWith('foobar', ''));
		$this->assertFalse(Str::iStartsWith('foobar', null));
		
		return;
	}
	
	/**
	 * Tests the Str::password() method
	 *
	 * 
	 */
	public function testPassword()
	{
		
	}
	
	/**
	 * Tests the Str::rand() method
	 */
	public function testRand()
	{
		$str = Str::rand(8);
		$this->assertEquals(strlen($str), 8);
		$this->assertEquals(strlen($str), 16);
		$this->assertEquals(strlen($str), 32);
		
		$str = Str::rand(8, 'upper');
		$this->assertEquals(strtoupper($str), $str);
		
		$str = Str::rand(8, 'lower')
		$this->assertEquals(strtolower($str), $str);
		
		$str = Str::rand(8, 'alpha');
		$this->assertEquals(preg_filter('/^[a-zA-Z]$/', '', $str), $str);
		
		$str = Str::rand(8, 'number');
		$this->assertEquals(preg_filter('/^[0-9]$/', '', $str), $str);
		
		$str = Str::rand(8, 'symbol');
		$this->assertEmpty(preg_filter('/^[a-zA-Z0-9]$/', '', $str));
	
		$str = Str::rand(8, ['alpha', 'number']);
		$this->assertEquals(preg_filter('/^[a-zA-Z0-9]$/', '', $str), $str);
		
		$str = Str::rand(8, ['number', 'symbol']);
		$this->assertEmpty(preg_filter('/^[a-zA-Z]$/', '', $str));
				
		return;
	}
	
	/**
	 * Tests the Str::splitOnFirstAlpha() method
	 */
	public function testSplitOnFirstAlpha()
	{
		$this->assertEquals(Str::splitOnFirstAlpha([]), []);
		$this->assertEquals(Str::splitOnFirstAlpha(['foo']), ['foo']);
		$this->assertEquals(Str::splitOnFirstAlpha(['123 foo']), ['123', 'foo']);
		$this->assertEquals(Str::splitOnFirstAlpha(['foo 123']), ['foo 123']);
		$this->assertEquals(Str::splitOnFirstAlpha(['123 456 foo']), ['123 456', 'foo']);
		$this->assertEquals(Str::splitOnFirstAlpha(['123 foo 456 bar']), ['123', 'foo 456 bar']);
		
		return;
	}
	
	/**
	 * Tests the Str::startsWith() method
	 */
	public function testStartsWith()
	{
		$this->assertTrue(Str::startsWith('foobar', 'foo'));
		
		$this->assertFalse(Str::startsWith('foobar', 'bar'));
		$this->assertFalse(Str::startsWith('foobar', 'FOO'));
		$this->assertFalse(Str::iStartsWith('foobar', ''));
		$this->assertFalse(Str::iStartsWith('foobar', null));
		
		return;
	}
	
	/**
	 * Tests the Str::strtobytes() method
	 */
	public function testStrtobytes()
	{
		$this->assertEquals(Str::strtobytes('1k'), 1024);
		$this->assertEquals(Str::strtobytes('1K'), 1024);
		$this->assertEquals(Str::strtobytes('1m'), 1048576);
		$this->assertEquals(Str::strtobytes('1M'), 1048576);
		$this->assertEquals(Str::strtobytes('1g'), 1073741824);
		$this->assertEquals(Str::strtobytes('1G'), 1073741824);
		
		return;
	}
	
	/**
	 * Tests the Str::strtocamelcase() method
	 */
	public function testStrtocamelcase()
	{
		$this->assertNull(Str::strtocamelcase(null));
		$this->assertTrue(Str::strtocamelcase('foo'), 'foo');
		$this->assertTrue(Str::strtocamelcase('FOO'), 'foo');
		$this->assertTrue(Str::strtocamelcase('foo bar'), 'fooBar');
		$this->assertTrue(Str::strtocamelcase('foo-bar'), 'fooBar');
		$this->assertTrue(Str::strtocamelcase('foo_bar'), 'fooBar');
		$this->assertTrue(Str::strtocamelcase('f!o%o@b#a(r'), 'fooBar');
		$this->assertTrue(Str::strtocamelcase('FOO bar'), 'fooBar');
		$this->assertTrue(Str::strtocamelcase('fOo BaR'), 'fooBar');
	
		return;
	}
	
	/**
	 * Tests the Str::truncate() method
	 */
	public function testTruncate()
	{
		$str = 'Lorem ipusm inum dolor amet';
		
		$this->assertEqual(Str::truncate('', 999), '');
		$this->assertEqual(Str::truncate($str, 0), '');
		$this->assertEqual(Str::truncate($str, 999), $str);
		
		$this->assertEqual(Str::truncate($str, 10), 'Lorem...');
		$this->assertEqual(Str::truncate($str, 10, ''), 'Lorem ipsu...');
		$this->assertEqual(Str::truncate($str, 10, ' ', '|||'), 'Lorem|||');
		
		return;
	}
}
 