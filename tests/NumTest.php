<?php
/**
 * The file for the NumTest class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2014 Jack Clayton
 * @license    MIT License <http://opensource.org/licenses/MIT>
 * @package    Jstewmc/PhpHelpers <https://github.com/jstewmc/php-helpers>
 */

use Jstewmc\PhpHelpers\Num;

class NumTest extends PHPUnit_Framework_TestCase
{
	/* !Data providers */
	
	/**
	 * Provides float (or float equivalents)
	 */
	public function provideFloatValues()
	{
		return array(
			array(-1.0),
			array(0.0),
			array(1.0),
			array('-1.0'),
			array('0.0'),
			array('1.0')
		);
	}
	
	/**
	 * Provides integer (or integer equivalents)
	 */
	public function provideIntegerValues()
	{
		return array(
			array(-1),
			array(0),
			array(1),
			array('-1'),
			array('0'),
			array('1')
		);
	}
	
	/**
	 * Provides a list of non-numeric values in PHP
	 */
	public function provideNonNumericValues()
	{
		return array(
			array(true),
			array('foo'),
			array(array()),
			array(new StdClass())
		);
	}
	
	/**
	 * Provides a list of non-string values in PHP
	 */
	public function provideNonStringValues()
	{
		return array(
			array(true),
			array(1),
			array(array()),
			array(new StdClass())
		);
	}
	
	
	/* !almostEqual() */
	
	/**
	 * almostEqual() should throw a BadMethodCallException on null arguments
	 */
	public function testAlmostEqual_throwsBadMethodCallException_ifAAndBAreNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Num::almostEqual(null, null);
		
		return;
	}
	
	/**
	 * almostEqual() should throw an InvalidArgumentException on non-numeric first 
	 *     argument
	 *
	 * @dataProvider provideNonNumericValues
	 */
	public function testAlmostEqual_throwsInvalidArgumentException_ifAIsNaN($value)
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::almostEqual($value, 1.0);
		
		return;	
	}
	
	/**
	 * almostEqual() should throw an InvalidArgumentException on a non-numeric second 
	 *     argument
	 *
	 * @dataProvider provideNonNumericValues
	 */
	public function testAlmostEqual_throwsInvalidArgumentException_ifBIsNaN($value)
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::almostEqual(1.0, $value);
		
		return;
	}
	
	/**
	 * almostEqual() should throw an InvalidArgumentException on a non-numeric third 
	 *     argument
	 * 
	 * @dataProvider provideNonNumericValues
	 */
	public function testAlmostEqual_throwsInvalidArgumentException_ifEpsilonIsNaN($value)
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::almostEqual(1.0, 1.0, $value);
		
		return;
	}
	
	/**
	 * almostEqual() should throw an InvalidArgumentException if third argument, epsilon, 
	 *     is zero
	 */
	public function testAlmostEqual_throwsInvalidArgumentException_ifEpsilonIsZero()
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::almostEqual(1.0, 1.0, 0);
		
		return;
	}
	
	/**
	 * almostEqual() should return true on equal floats
	 */
	public function testAlmostEqual_returnsTrue_ifFloatsAreEqual()
	{
		return $this->assertTrue(Num::almostEqual(1/10, 0.1));
	}
	
	/**
	 * almostEqual() should return false on unequal floats
	 */
	public function testAlmostEqual_returnsFalse_ifFloatsAreUnequal()
	{
		return $this->assertFalse(Num::almostEqual(0.2, 0.7));
	}
	
	/**
	 * almostEqual() should return true on equal integers
	 */
	public function testAlmostEqual_returnsTrue_ifIntegersAreEqual()
	{
		return $this->assertTrue(Num::almostEqual(1, 1));
	}
	
	/**
	 * almostEqual() should return false on unequal integers
	 */
	public function testAlmostEqual_returnsFalse_ifIntegersAreUnequal()
	{
		return $this->assertFalse(Num::almostEqual(1, 2));
	}
	
	
	/* !bound() */
	
	/**
	 * bound() should throw a BadMethodCallException if number is null
	 */
	public function testBound_throwsBadMethodCallException_ifValueIsNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Num::bound(null);
		
		return;
	}
	
	/**
	 * bound() should throw an InvalidArgumentException if number is not a number
	 *
	 * @dataProvider  provideNonNumericValues
	 */
	public function testBound_throwsInvalidArgumentException_ifNumberIsNaN($value)
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::bound($value, 1);
		
		return;
	}
	
	/**
	 * bound() should throw an InvalidArgumentException if lower bound is not a number
	 *
	 * @dataProvider  provideNonNumericValues
	 */
	public function testBound_throwsInvalidArgumentException_ifLowerIsNaN($value)
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::bound(1, $value);
		
		return;
	}
	
	/**
	 * bound() should throw an InvalidArgumentException if upper bound is not a number
	 * 
	 * @dataProvider provideNonNumericValues
	 */
	public function testBound_throwsInvalidArgumentException_ifUpperIsNaN($value)
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::bound(1, 0, $value);
		
		return;
	}
	
	/**
	 * bound() should throw an InvalidArgumentException if the lower bound is greater than
	 *     the upper bound
	 */
	public function testBound_throwsInvalidArgumentException_ifLowerIsGreaterThanUpper()
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::bound(1, 2, 0);
		
		return;
	}
	
	/**
	 * bound() should return the upper bound if the number is greater
	 */
	public function testBound_returnsUpper_ifNumberIsGreaterThanUpper()
	{
		$this->assertEquals(Num::bound(2, null, 1), 1);
	}
	
	/**
	 * bound() should return the lower bound if the value is lesser
	 */
	public function testBound_returnsLower_ifNumberIsLessThanLower()
	{
		$this->assertEquals(Num::bound(1, 2), 2);
	}
	
	/**
	 * bound() should return the number if it's between the upper and lower bound
	 */
	public function testBound_returnsNumber_ifNumberIsBetweenLowerAndUpper()
	{
		$this->assertEquals(Num::bound(1, 0, 2), 1);
	}
	
	
	/* !ceilTo() */
	
	/**
	 * ceilTo() should throw a BadMethodCallException if the number is null
	 */
	public function testCeilTo_throwsBadMethodCallException_ifNumberIsNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Num::ceilTo(null);
		
		return;
	}
	
	/**
	 * ceilTo() should throw an InvalidArgumentException if the number is not a number
	 *
	 * @dataProvider provideNonNumericValues
	 */
	public function testCeilTo_throwsInvalidArgumentException_ifNumberIsNaN($value)
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::ceilTo($value);
		
		return;
	}
	
	/**
	 * ceilTo() should throw an InvalidArgumentException if the multiple is not a number
	 *
	 * @dataProvider provideNonNumericValues
	 */
	public function testCeilTo_throwsInvalidArgumentException_ifMultipleIsNaN($value)
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::ceilTo(1, $value);
		
		return;
	}
	
	/**
	 * ceilTo() should throw an InvalidArgumentException if the multiple is zero
	 */
	public function testCeilTo_throwsInvalidArgumentException_ifMultipleIsZero()
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::ceilTo(1, 0);
		
		return;
	}
	
	/**
	 * ceilTo() should return the same value as PHP's ceil() method if the multiple is
	 *     omitted
	 */
	public function testCeilTo_returnsPhpNativeCeil_ifMultipleIsOmitted()
	{
		return $this->assertEquals(Num::ceilTo(2.5), ceil(2.5));
	}
	
	/**
	 * ceilTo() should return the integer ceiling-ed to the nearest multiple
	 */
	public function testCeilTo_returnsCeiling_ifNumberAndMultipleAreIntegers()
	{
		return $this->assertEquals(Num::ceilTo(7, 10), 10);
	}
	
	/**
	 * ceilTo() should return the float ceiling-ed to the nearest multiple
	 */
	public function testCeilTo_returnsCeiling_ifNumberAndMultipleAreFloats()
	{
		return $this->assertEquals(Num::ceilTo(3.5, 1.5), 4.5);
	}
	
	/**
	 * ceilTo() should return the integer ceiling-ed to the nearest multiple
	 */
	public function testCeilTo_returnsCeiling_ifNumberIsIntegerAndMultipleIsFloat()
	{
		$this->assertEquals(Num::ceilTo(2, 1.5), 3);
	}
	
	/**
	 * ceilTo() should return the integer ceiling-ed to the nearest multiple
	 */
	public function testCeilTo_returnsCeiling_ifNumberIsFloatAndMultipleIsInteger()
	{
		$this->assertEquals(Num::ceilTo(2.5, 2), 4);
	}
	
	
	/* !floorTo() */
	
	/**
	 * floorTo() should throw a BadMethodCallException if the number is null
	 */
	public function testFloorTo_throwsBadMethodCallException_ifNumberIsNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Num::floorTo(null);
		
		return;
	}
	
	/**
	 * floorTo() should throw an InvalidArgumentException if the number is not a number
	 *
	 * @dataProvider provideNonNumericValues
	 */
	public function testFloorTo_throwsInvalidArgumentException_ifNumberIsNaN($value)
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::floorTo($value);
		
		return;
	}
	
	/**
	 * floorTo() should throw an InvalidArgumentException if the multiple is not a number
	 *
	 * @dataProvider provideNonNumericValues
	 */
	public function testFloorTo_throwsInvalidArgumentException_ifMultipleIsNaN($value)
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::floorTo(1, $value);
		
		return;
	}
	
	/**
	 * floorTo() should throw an InvalidArgumentException if the multiple is zero
	 */
	public function testFloorTo_throwsInvalidArgumentException_ifMultipleIsZero()
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::floorTo(1, 0);
		
		return;
	}
	
	/**
	 * floorTo() should return the same value as PHP's floor() method if the multiple is
	 *     omitted
	 */
	public function testFloorTo_returnsPhpNativeCeil_ifMultipleIsOmitted()
	{
		return $this->assertEquals(Num::floorTo(2.5), floor(2.5));
	}
	
	/**
	 * floorTo() should return the integer floored to the nearest multiple
	 */
	public function testFloorTo_returnsFloor_ifNumberAndMultipleAreIntegers()
	{
		return $this->assertEquals(Num::floorTo(5, 2), 4);
	}
	
	/**
	 * floorTo() should return the float floored to the nearest multiple
	 */
	public function testCeilTo_returnsFloor_ifNumberAndMultipleAreFloats()
	{
		return $this->assertEquals(Num::floorTo(3.5, 1.5), 3);
	}
	
	/**
	 * floorTo() should return the integer ceiling-ed to the nearest multiple
	 */
	public function testFloorTo_returnsFloor_ifNumberIsIntegerAndMultipleIsFloat()
	{
		$this->assertEquals(Num::floorTo(2, 1.5), 1.5);
	}
	
	/**
	 * floorTo() should return the integer ceiling-ed to the nearest multiple
	 */
	public function testFloorTo_returnsFloor_ifNumberIsFloatAndMultipleIsInteger()
	{
		$this->assertEquals(Num::floorTo(2.5, 2), 2);
	}
	
	
	/* !isId() */
	
	/**
	 * isId() should throw a BadMethodCallException if datatype is null
	 */
	public function testIsId_throwsBadMethodCallException_ifDatatypeIsNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Num::isId(1, null);
		
		return;
	}
	
	/**
	 * isId() should throw an InvalidArgumentException if datatype is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testIsId_throwsInvalidArgumentException_ifDatatypeIsNotAString($value)
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::isId(1, $value);
		
		return;
	}
	
	/**
	 * isId() should throw an InvalidArgumentException if datatype is not a valid value
	 */
	public function testIdIs_throwsInvalidArgumentException_ifDatatypeIsNotValue()
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::isId(1, 'foo');
		
		return;
	}
	
	/**
	 * isId() should return false if number is null
	 */
	public function testIsId_returnsFalse_ifNumberIsNull()
	{
		return $this->assertFalse(Num::isId(null));
	}
	
	/**
	 * isId() should return false if number is non-numeric
	 *
	 * @dataProvider provideNonNumericValues
	 */
	public function testIsId_returnsFalse_ifNumberIsNonNumeric($value)
	{
		return $this->assertFalse(Num::isId($value));
	}
	
	/**
	 * isId() should return false if number is zero
	 */
	public function testIsId_returnsFalse_ifNumberIsZero()
	{
		return $this->assertFalse(Num::isId(0));
	}
	
	/**
	 * isId() should return false if number is a float
	 */
	public function testIsId_returnsFalse_ifNumberIsFloat()
	{
		return $this->assertFalse(Num::isId(1.2));
	}
	
	/**
	 * isId() should return false if number is greater than datatype's max unsigned value
	 */
	public function testIdId_returnsFalse_ifNumberIsGreaterThanDatatypeMax()
	{
		return $this->assertFalse(Num::isId(999, 'tiny'));
	}
	
	/** 
	 * isId() should return true if number is a positive, integer that's below the
	 *     datatype's max unsigned value
	 */
	public function testIsId_returnsTrue_ifNumberIsPositiveIntegerBelowDatatypeMaxUnsignedValue()
	{
		return $this->assertTrue(Num::isId(1, 'tiny'));
	}
	
	
	/* !isInt() */
	
	/**
	 * isInt() should return false on null
	 */
	public function testIsInt_returnsFalse_ifNumberIsNull()
	{
		return $this->assertFalse(Num::isInt(null));
	}
	
	/**
	 * isInt() should return false if number is not a number
	 *
	 * @dataProvider provideNonNumericValues
	 */
	public function testIsInt_returnsFalse_ifNumberIsNaN($number)
	{
		return $this->assertFalse(Num::isInt($number));
	}
	
	/**
	 * isInt() should return true if number is an integer
	 *
	 * @dataProvider provideIntegerValues
	 */
	public function testIsInt_returnsTrue_ifNumberIsInteger($number)
	{
		return $this->assertTrue(Num::isInt($number));
	}
	
	/**
	 * isInt() should return false if number is a float
	 *
	 * @dataProvider provideFloatValues
	 */
	public function testIsInt_returnsFalse_ifNumberIsFloat($number)
	{
		return $this->assertFalse(Num::isInt($number));
	}
	
	
	/* !isNumeric() */
	
	/**
	 * isNumeric() should return false if number is null
	 */
	public function testIsNumeric_returnsFalse_ifNumberIsNull()
	{
		return $this->assertFalse(Num::isNumeric(null));
	}
	
	/**
	 * isNumeric() should return false if number is not a number
	 *
	 * @dataProvider  provideNonNumericValues
	 */
	public function testIsNumeric_returnsFalse_ifNumberIsNaN($number)
	{
		return $this->assertFalse(Num::isNumeric($number));
	}
	
	/**
	 * isNumeric() should return true if number is a float
	 *
	 * @dataProvider provideIntegerValues
	 */
	public function testIsNumeric_returnsTrue_ifNumberIsFloat($number)
	{
		return $this->assertTrue(Num::isNumeric($number));
	}
	
	/**
	 * isNumeric() should return true if number is an integer
	 *
	 * @dataProvider  provideIntegerValues
	 */
	public function testIsNumeric_returnsTrue_ifNumberIsInt($number)
	{
		return $this->assertTrue(Num::isNumeric($number));
	}
	
	/**
	 * isNumeric() should return true if number is a fraction
	 */
	public function testIsNumeric_returnsTrue_ifNumberIsFraction()
	{
		return $this->assertTrue(Num::isNumeric('1/2'));
	}
	
	/**
	 * isNumeric() should return true if number is a mixed number
	 */
	public function testIsNumeric_returnsTrue_ifNumberIsMixed()
	{
		return $this->assertTrue(Num::isNumeric('1 1/2'));
	}
	 
	/**
	 * isNumeric() should return true if number if a comma-separated number
	 */
	public function testIsNumeric_returnsTrue_ifNumberIsCommaSeparated()
	{
		return $this->assertTrue(Num::isNumeric('1,000'));
	}
	
	
	/* !isZero() */
	
	/**
	 * isZero() should return false if number is null
	 */
	public function testIsZero_returnsFalse_ifNumberIsNull()
	{
		return $this->assertFalse(Num::isZero(null));
	}
	
	/**
	 * isZero() should return false if number is not a number
	 *
	 * @dataProvider  provideNonNumericValues
	 */
	public function testIsZero_returnsFalse_ifNumberIsNaN($number)
	{
		return $this->assertFalse(Num::isZero($number));
	}
	
	/**
	 * isZero() should return false if number is not zero
	 */
	public function testIsZero_returnsFalse_ifNumberIsNotZero()
	{
		return $this->assertFalse(Num::isZero(1));
	}
	
	/**
	 * isZero() should return true if number is zero
	 */
	public function testIsZero_returnsTrue_ifNumberIsZero()
	{
		return $this->assertTrue(Num::isZero(0));
	}
	
	
	/* !normalize() */
	
	/**
	 * normalize() should throw a BadMethodCallException if number and max are null
	 */
	public function testNormalize_throwsBadMethodCallException_ifNumberAndMaxAreNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Num::normalize(null, null);
		
		return;
	}
	
	/**
	 * normalize() should throw an InvalidArgumentException if number is not a number
	 *
	 * @dataProvider  provideNonNumericValues
	 */
	public function testNormalize_throwsInvalidArgumentException_ifNumberIsNaN($number)
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::normalize($number, 1);
		
		return;
	}
	
	/**
	 * normalize() should throw an InvalidArgumentException if max is not a number
	 *
	 * @dataProvider  provideNonNumericValues
	 */
	public function testNormalize_throwsInvalidArgumentException_ifMaxIsNaN($number)
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::normalize(1, $number);
		
		return;
	}
	
	/**
	 * normalize() should throw an InvalidArgumentException if max is zero
	 */
	public function testNormalize_throwsInvalidArgumentException_ifMaxIsZero()
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::normalize(1, 0);
		
		return;
	}
	
	/**
	 * normalize() should return zero if the quotient is less than zero
	 */
	public function testNormalize_returnsZero_ifQuotientIsLessThanZero()
	{
		return $this->assertEquals(Num::normalize(-1, 1), 0);
	}
	
	/**
	 * normalize() should return one if the quotient is greater than one
	 */
	public function testNormalize_returnsOne_ifQuotientIsGreaterThanOne()
	{
		return $this->assertEquals(Num::normalize(2, 1), 1);
	}
	
	/**
	 * normalize() should return the quotient if it's greater than or equal to zero
	 *     and less than or equal to one
	 */
	public function testNormalize_returnsQuotient_ifQuotientBetweenZeroAndOne()
	{
		return $this->assertEquals(Num::normalize(0.5, 1), 0.5);
	}
	
	
	/* !roundTo() */
	
	/**
	 * roundTo() should throw a BadMethodCallException if number and multiple are null
	 */
	public function testRoundTo_throwsBadMethodCallException_ifNumberAndMultipleAreNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Num::roundTo(null, null);
		
		return;
	}
	
	/**
	 * roundTo() should throw an InvalidArgumentException if number is not a number
	 *
	 * @dataProvider  provideNonNumericValues
	 */
	public function testRoundTo_throwsInvalidArgumentException_ifNumberIsNaN($number)
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::roundTo($number, 1);
		
		return;
	}
	
	/**
	 * roundTo() should throw an InvalidArgumentException if multiple is not a number
	 *
	 * @dataProvider provideNonNumericValues
	 */
	public function testRoundTo_throwsInvalidArgumentException_ifMultipleIsNaN($multiple)
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::roundTo(1, $multiple);
		
		return;
	}
	
	/**
	 * roundTo() should throw an exception if multiple is zero
	 */
	public function testRoundTo_throwsInvalidArgumentException_ifMultipleIsZero()
	{
		$this->setExpectedException('InvalidArgumentException');
		Num::roundTo(1, 0);
		
		return;
	}
	
	/**
	 * roundTo() should return PHP's native round if multiple is omitted
	 */
	public function testRoundTo_returnsPhpRound_ifMultipleIsOmitted()
	{
		$this->assertEquals(Num::roundTo(1.5), round(1.5));
	}
	
	/**
	 * roundTo() should return the rounded number if both number and multiple are integers
	 */
	public function testRoundTo_returnsRound_ifNumberAndMultipleAreIntegers()
	{
		$this->assertEquals(Num::roundTo(1, 2), 2);
	}
	
	/**
	 * roundTo() should return the rounded number if both number and multiple are floats
	 */
	public function testRoundTo_returnsRound_ifNumberAndMultipleAreFloats()
	{
		$this->assertEquals(Num::roundTo(1.7, 1.5), 1.5);
	}
	
	/**
	 * roundTo() should return the rounded number if number is integer and multiple is float
	 */
	public function testRoundTo_returnsRound_ifNumberIsIntegerAndMultipleIsFloat()
	{
		$this->assertEquals(Num::roundTo(2, 1.5), 1.5);
	}
	
	/**
	 * roundTo() should return the rounded number if number is a float and multiple is an integer
	 */
	public function testRoundTo_returnsRound_ifNumberIsFloatAndMultipleIsInteger()
	{
		$this->assertEquals(Num::roundTo(1.1, 2), 2);
	}
	
	
	/* !val() */
	
	/**
	 * val() should return integer if var is a bool
	 */
	public function testVal_returnsInteger_ifVarIsBool()
	{
		return $this->assertEquals(Num::val(true), 1);
	}
	
	/**
	 * val() should return float if var is a float
	 */
	 public function testVal_returnsFloat_ifVarIsAFloat()
	 {
		 return $this->assertEquals(Num::val(1.2), 1.2);
	 }
	 
	 /**
	  * val() should return integer if var is an integer
	  */
	 public function testVal_returnsInteger_ifVarIsAnInteger()
	 {
		 return $this->assertEquals(Num::val(1), 1);
	 }
	 
	 /**
	  * val() should return float if var is a string float
	  */
	 public function testVal_returnsFloat_ifVarIsAStringFloat()
	 {
		 return $this->assertEquals(Num::val('1.0'), 1.0);
	 }
	 
	 /**
	  * val() should return integer if var is a string integer
	  */
	 public function testVal_returnsInteger_ifVarIsStringInteger()
	 {
		 return $this->assertEquals(Num::val('1'), 1);
	 }
	 
	 /**
	  * val() should return a float if var is a fraction
	  */
	 public function testVal_returnsFloat_ifVarIsAStringFraction()
	 {
		 return $this->assertEquals(Num::val('1/2'), 0.5);
	 }
	 
	 /**
	  * val() should return a flaot if var is a mixed number
	  */
	 public function testVal_returnsFloat_ifVarIsAStringMixedNumber()
	 {
		 return $this->assertEquals(Num::val('1 1/2'), 1.5);
	 }
	 
	 /**
	  * val() should return an integer if var is a comma-separated integer
	  */
	 public function testVal_returnsInteger_ifVarIsAStringCommaSeparatedInteger()
	 {
		 return $this->assertEquals(Num::val('1,000'), 1000);
	 }
	 
	 /**
	  * val() should return an integer if var is a comma-separated float
	  */
	 public function testVal_returnsFloat_ifVarIsAStringCommaSeparatedFloat()
	 {
		 return $this->assertEquals(Num::val('1,000.5'), 1000.5);
	 }
	 
	 /**
	  * val() should return a zero if var is a non-numeric string
	  */
	 public function testVal_returnsZero_ifVarIsANonNumericString()
	 {
		 return $this->assertEquals(Num::val('foo'), 0);
	 }
	 
	 /**
	  * val() should return a zero if var is an empty array
	  */
	 public function testVal_returnsZero_ifVarIsAnEmptyArray()
	 {
		 return $this->assertEquals(Num::val(array()), 0);
	 }
	 
	 /**
	  * val() should return one if var is a non-empty array
	  */
	 public function testVal_returnsOne_ifVarIsANonEmptyArray()
	 {
		 return $this->assertEquals(Num::val(array(1)), 1);
	 }
	 
	 /**
	  * val() should return one if var is an object
	  */
	 public function testVal_returnsOne_ifVarIsAnObject()
	 {
		 return $this->assertEquals(Num::val(new StdClass()), 1);
	 }
}
