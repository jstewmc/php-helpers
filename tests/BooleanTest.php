<?php

use Jstewmc\PhpHelpers\Boolean;

/**
 * @group  jack
 */
class BooleanTest extends \PHPUnit\Framework\TestCase
{
	/* !Data providers */

	/**
	 * Provides an array of values considered false by Boolean::val()
	 */
	public function falsyProvider()
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
	 * Provides an array of values considered true by Boolean::val()
	 */
	public function truthyProvider()
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

	public function testBooltostrThrowsInvalidArgumentExceptionWhenFormatIsInvalid(): void
	{
		$this->expectException(InvalidArgumentException::class);

		Boolean::booltostr(true, 'foo');
	}

	public function testBooltostrReturnsStringTrueWhenBoolIsTrue(): void
	{
		$this->assertEquals(Boolean::booltostr(true), 'true');
	}

	public function testBooltostrReturnsStringFalseWhenBoolIsFalse(): void
	{
		$this->assertEquals(Boolean::booltostr(false), 'false');
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
