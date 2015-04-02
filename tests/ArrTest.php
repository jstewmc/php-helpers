<?php
/**
 * The file for the Arr test class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2014 Jack Clayton
 * @license    MIT License <http://opensource.org/licenses/MIT>
 * @package    Jstewmc/PhpHelpers <https://github.com/jstewmc/php-helpers>
 */

use Jstewmc\PhpHelpers\Arr;

/**
 * A class to test the object methods
 */
class Foo 
{
	public $bar;
	
	public function __construct($bar)
	{
		$this->bar = $bar;
	}
	
	public function bar()
	{
		return $this->bar;
	}	
}

/**
 * A class to test the Arr class
 */
class ArrTest extends PHPUnit_Framework_TestCase
{	
	/* !Data providers */
	
	/**
	 * Provides non-array values
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
	 * Provides an array of non-bool arguments
	 */
	public function provideNonBoolValues()
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
	 * Provides non-callable values
	 */
	public function provideNonCallableValues()
	{
		return array(
			array(true),
			array(1),
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
	public function provideNonStringAndNonArrayValues()
	{
		return array(
			array(true),
			array(1),
			array(1.0),
			array(new StdClass())
		);
	}
	

	/* !diff() */
	
	/**
	 * diff() should return array if diff is insert
	 */
	public function testDiff_returnsArray_ifDiffIsInsert()
	{
		$from = ['foo'];
		$to   = ['foo', 'bar'];
		
		$expected = [['value' => 'foo', 'mask' => 0], ['value' => 'bar', 'mask' => 1]];
		$actual   = Arr::diff($from, $to);
		
		$this->assertEquals($expected, $actual);
		
		return;  
	}
	
	/**
	 * diff() should return array if diff is delete
	 */
	public function testDiff_returnsArray_ifDiffIsDelete()
	{
		$from = ['foo', 'bar'];
		$to   = ['foo'];
		
		$expected = [['value' => 'foo', 'mask' => 0], ['value' => 'bar', 'mask' => -1]];
		$actual   = Arr::diff($from, $to);
		
		$this->assertEquals($expected, $actual);
		
		return;
	}
	
	/**
	 * diff() should return array if diff is update
	 */
	public function testDiff_returnsArray_ifDiffIsUpdate()
	{
		$from = ['foo'];
		$to   = ['bar'];
		
		$expected = [['value' => 'foo', 'mask' => -1], ['value' => 'bar', 'mask' => 1]];
		$actual   = Arr::diff($from, $to);
		
		$this->assertEquals($expected, $actual);
		
		return;
	}

	
	/* !filterBykey() */
	
	/**
	 * filterBykey() should throw a BadMethodCallException if arguments are null
	 */
	public function testFilterByKey_throwsBadMethodCallException_ifArgumentsAreNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Arr::filterBykey(null, null);
		
		return;
	}
	
	/**
	 * filterBykey() should throw an InvalidArgumentException if array is not an array
	 *
	 * @dataProvider provideNonArrayValues
	 */
	public function testFilterByKey_throwsInvalidArgumentException_ifArrayIsNotAnArray($array)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::filterBykey($array, function () { return true; });
		
		return;
	}
	
	/**
	 * filterBykey() should throw an InvalidArgumentException if callback is not an array
	 *
	 * @dataProvider provideNonCallableValues
	 */
	public function testFilterByKey_throwsInvalidArgumentException_ifCallbackIsNotCallable($callback)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::filterBykey(array(1), $callback);
		
		return;
	}
	
	/**
	 * filterBykey() should return an empty array if the input array is empty
	 */
	public function testFilterByKey_returnsEmptyArray_ifInputArrayIsEmpty()
	{
		$result = Arr::filterBykey(array(), function () { return true; });
	
		return $this->assertTrue(is_array($result) && empty($result));
	}
	
	/**
	 * filterBykey() should return an empty array if no keys match the callback
	 */
	public function testFilterByKey_returnsEmptyArray_ifKeysDoNotMatch()
	{
		$result = Arr::filterByKey(array('foo' => 'bar'), function () { return false; });
		
		return $this->assertTrue(is_array($result) && empty($result));
	}
	
	/**
	 * filterByKey() should return an array with matching keys
	 */
	public function testFilterByKey_returnsArray_ifKeysDoMatch()
	{
		$input    = array('foo' => 'bar', 'baz' => 'qux', 'quux' => 'corge');
		$expected = array('foo' => 'bar', 'baz' => 'qux');
		$actual   = Arr::filterByKey($input, function ($k) {
			return in_array($k, array('foo', 'baz'));
		});
		
		return $this->assertEquals($actual, $expected);
	}
	
	
	/* !filterByKeyPrefix() */
	
	/**
	 * filterByKeyPrefix() should throw a BadMethodCallException if array and prefix are null
	 */
	public function testFilterByKeyPrefix_throwsBadMethodCallException_ifArgumentsAreNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Arr::filterByKeyPrefix(null, null);
		
		return;
	}

	/**
	 * filterByKeyPrefix() should throw an InvalidArgumentException is array is not an array
	 *
	 * @dataProvider provideNonArrayValues
	 */	
	public function testFilterByKeyPrefix_throwsInvalidArgumentException_ifArrayIsNotAnArray($array)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::filterByKeyPrefix($array, 'foo');
		
		return;
	}
	
	/**
	 * filterByKeyPrefix() should throw an InvalidArgumentException if prefix is not a string
	 *
	 * @dataProvider  provideNonStringValues
	 */
	public function testFilterByKeyPrefix_throwsInvalidArgumentException_ifPrefixIsNotAString($string)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::filterByKeyPrefix(array(), $string);
		
		return;
	}
	
	/**
	 * filterByKeyPrefix() should return empty array if array is empty
	 */
	public function testFilterByKeyPrefix_returnsEmptyArray_ifInputArrayIsEmpty()
	{
		$result = Arr::filterByKeyPrefix(array(), 'foo');
		
		return $this->assertTrue(is_array($result) && empty($result));
	}
	
	/**
	 * filterByKeyPrefix() should return empty array if no keys match
	 */
	public function testFilterByKeyPrefix_returnsEmptyArray_ifKeysDoNotMatch()
	{
		$result = Arr::filterByKeyPrefix(array('foo' => 'bar', 'baz' => 'qux'), 'quux');
		
		return $this->assertTrue(is_array($result) && empty($result));
	}
	
	/**
	 * filterByKeyPrefix() should return an array of matching keys
	 */
	public function testFilterByKeyPrefix_returnsArray_ifKeysDoMatch()
	{
		$input    = array('foo' => 'bar', 'baz' => 'qux', 'quux' => 'corge');
		$actual   = Arr::filterByKeyPrefix($input, 'f');
		$expected = array('foo' => 'bar');
		
		return $this->assertEquals($actual, $expected);
	}
	
	
	/* !inArray() */ 
	
	/**
	 * search() should throw a BadMethodCallException if arguments are null
	 */
	public function testInArray_throwsBadMethodCallException_ifArgumentsAreNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Arr::inArray(null, null);
		
		return;
	}
	
	/**
	 * inArray() should throw an InvalidArgumentException if $array is not an array
	 *
	 * @dataProvider  provideNonArrayValues
	 */
	public function testInArray_throwsInvalidArgumentException_ifArrayIsNotAnArray($array)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::inArray($array, 'foo');
		
		return;
	}
	
	/**
	 * inArray() should throw an InvalidArgumentException if $search is not a string
	 *
	 * @dataProvider  provideNonStringValues
	 */
	public function testInArray_throwsInvalidArgumentException_ifSearchIsNotAString($search) 
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::inArray(array(), $search);
		
		return;
	}
	
	/**
	 * inArray() should return false if the input array is empty
	 */
	public function testInArray_returnsFalse_ifInputArrayIsEmpty()
	{
		return $this->assertFalse(Arr::inArray('foo*', array()));
	}
	
	/**
	 * inArray() should return false if matches do not exist
	 */
	public function testInArray_returnsFalse_ifMatchDoesNotExist()
	{
		return $this->assertFalse(Arr::inArray('q*', array('foo', 'bar', 'baz')));
	}
	
	/**
	 * inArray() should return true if exact matches exist
	 */
	public function testInArray_returnsTrue_ifExactMatchExists()
	{
		return $this->assertTrue(Arr::inArray('foo', array('foo', 'bar', 'baz')));
	}
	
	/**
	 * inArray() should return true if begins-with match exists
	 */
	public function testInArray_returnsTrue_ifBeginsWithMatchExists()
	{
		return $this->assertTrue(Arr::inArray('f*', array('foo', 'bar', 'baz')));
	}
	
	/**
	 * inArray() should return true if ends-with match exists
	 */
	public function testInArray_returnsTrue_ifEndsWithMatchExists()
	{
		return $this->assertTrue(Arr::inArray('*z', array('foo', 'bar', 'baz')));
	}
	
	/** 
	 * inArray() should return true if contains match exists
	 */
	public function testInArray_returnsTrue_ifContainsMatchExists()
	{
		return $this->assertTrue(Arr::inArray('*a*', array('foo', 'bar', 'baz')));
	}


	/* !isAssoc() */
	
	/**
	 * isAssoc() should return false if array is null
	 */
	public function testIsAssoc_returnsFalse_ifArrayIsNull()
	{
		return $this->assertFalse(Arr::isAssoc(null));
	}
	
	/**
	 * isAssoc() should return false if array is not an array
	 *
	 * @dataProvider  provideNonArrayValues
	 */
	public function testIsAssoc_returnsFalse_ifArrayIsNotAnArray($array)
	{
		return $this->assertFalse(Arr::isAssoc($array));
	}
	
	/**
	 * isAssoc() should return false if array does not have string key
	 *
	 * Keep in mind, PHP will convert a string integer to an integer.
	 */
	public function testIsAssoc_returnsFalse_ifArrayDoesNotHaveStringKey()
	{
		return $this->assertFalse(Arr::isAssoc(array(1 => 'foo', '2' => 'bar')));
	}
	
	/** 
	 * isAssoc() should return true if one or more string keys exist
	 */
	public function testIsAssoc_returnsTrue_ifArrayHasStringKey()
	{
		return $this->assertTrue(Arr::isAssoc(array('foo' => 'bar', 2 => 'baz')));
	}
	
	
	/* !isEmpty() */
	
	/**
	 * isEmpty() should throw a BadMethodCallException if key and array are null
	 */
	public function testIsEmpty_throwsBadMethodCallException_ifArgumentsAreNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Arr::isEmpty(null, null);
		
		return;
	}
	
	/**
	 * isEmpty() should throw an InvalidArgumentException if key is not a string
	 *
	 * @dataProvider  provideNonStringValues()
	 */
	public function testIsEmpty_throwsInvalidArgumentException_ifKeyIsNotAString($key)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::isEmpty($key, array());
		
		return;
	}
	
	/**
	 * isEmpty() should throw an InvalidArgumentException if array is not an array
	 *
	 * @dataProvider  provideNonArrayValues
	 */
	public function testIsEmpty_throwsInvalidArgumentException_ifArrayIsNotArray($array)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::isEmpty('foo', $array);
		
		return;
	}
	
	/**
	 * isEmpty() should throw an InvalidArgumentException if zero is not a bool value
	 *
	 * @dataProvider  provideNonBoolValues
	 */
	public function testIsEmpty_throwsBadMethodCallException_ifZeroIsNotNull($zero)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::isEmpty('foo', array(), $zero);
		
		return;
	}
	
	/**
	 * isEmpty() should return true if array is empty
	 */
	public function testIsEmpty_returnsTrue_ifInputArrayIsEmpty()
	{
		return $this->assertTrue(Arr::isEmpty('foo', array()));
	}
	
	/**
	 * isEmpty() should return true if key does not exist
	 */
	public function testIsEmpty_returnsTrue_ifKeyDoesNotExist()
	{
		return $this->assertTrue(Arr::isEmpty('foo', array('bar' => true)));
	}
	
	/**
	 * isEmpty() should return true if value is empty
	 */
	public function testIsEmpty_returnsTrue_ifValueIsEmpty()
	{
		return $this->assertTrue(Arr::isEmpty('foo', array('foo' => null)));
	}
	
	/**
	 * isEmpty() should return false if value is not empty
	 */
	public function testIsEmpty_returnFalse_ifValueIsNotEmpty()
	{
		return $this->assertFalse(Arr::isEmpty('foo', array('foo' => true)));
	}
	
	/**
	 * isEmpty() should return false if value is zero and zero is not empty
	 */
	public function testIsEmpty_returnFalse_ifValueIsZeroAndZeroIsNotEmpty()
	{
		return $this->assertFalse(Arr::isEmpty('foo', array('foo' => 0), false));
	}

	
	/* !keyStringReplace() */
	
	/**
	 * keyStringReplace() should throw a BadMethodCallException if arguments are null
	 */
	public function testKeyStringReplace_throwsBadMethodCallException_ifArgumentsAreNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Arr::keyStringReplace(null, null, null);
		
		return;
	}
	
	/**
	 * keyStringReplace() should throw an InvalidArgumentException if search is not a string
	 *
	 * @dataProvider  provideNonStringAndNonArrayValues
	 */
	public function testKeyStringReplace_throwsInvalidArgumentException_ifSearchIsNotAString($search)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::keyStringReplace($search, 'foo', array());
		
		return;
	}
	
	/** 
	 * keyStringReplace() should throw an InvalidArgumentException if replace is not a string
	 *
	 * @dataProvider  provideNonStringAndNonArrayValues
	 */
	public function testKeyStringReplace_throwsInvalidArgumentException_ifReplaceIsNotAString($replace)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::keyStringReplace('foo', $replace, array());
		
		return;	
	}
	
	/**
	 * keyStringReplace() should throw an InvalidArgumentException if array is not an array
	 *
	 * @dataProvider  provideNonArrayValues
	 */
	public function testKeyStringReplace_throwsInvalidArgumentException_ifArrayIsNotAnArray($array)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::keyStringReplace('foo', 'bar', $array);
		
		return;
	}
	
	/**
	 * keyStringReplace() should return empty array if input array is empty
	 */
	public function testKeyStringReplace_returnsEmptyArray_ifInputArrayIsEmpty()
	{
		$result = Arr::keyStringReplace('foo', 'bar', array());
		
		return $this->assertTrue(is_array($result) && empty($result));
	}
	
	/**
	 * keyStringReplace() should return the original array if keys do not match
	 */
	public function testKeyStringReplace_returnsArray_ifKeysDoNotMatch()
	{
		$array    = array('baz' => 'qux', 'quux' => 'corge');
		$actual   = Arr::keyStringReplace('foo', 'bar', $array);
		$expected = $array;
		
		return $this->assertEquals($actual, $expected);
	}
	
	/**
	 * keyStringReplace() should return a replaced array if keys match
	 */
	public function testKeyStringReplace_returnsArray_ifKeysDoMatch()
	{
		$array    = array('foo' => 'bar', 'foobar' => 'baz', 'qux' => 'quux');
		$actual   = Arr::keyStringReplace('foo', 'bar', $array);
		$expected = array('bar' => 'bar', 'barbar' => 'baz', 'qux' => 'quux');
		
		return $this->assertEquals($actual, $expected);
	}
	
	
	/* !permute() */
	
	/**
	 * permute() should return an array of permutations for two elements
	 */
	public function testPermute_returnsArray_ifTwoElements()
	{
		$array = ['foo', 'bar'];
		
		$expected = [['foo', 'bar'], ['bar', 'foo']];
		$actual   = Arr::permute($array);
		
		// reduce $expected and $actual to single-dimensional arrays so we can use
		//     array_diff to compare (because the elements will be out of order)
		//
		$expected = array_map(function ($v) {
			return implode('', $v);
		}, $expected);
		
		$actual = array_map(function ($v) {
			return implode('', $v);
		}, $actual);
		
		$this->assertEquals(0, count(array_diff($expected, $actual)));
		
		$this->assertEquals($expected, $actual);
		
		return;
	}
	
	/**
	 * permute() should return an array of permutations for three elements
	 */
	public function testPermute_returnsArray_ifThreeElements()
	{
		$array = ['foo', 'bar', 'baz'];
		
		$expected = [
			['foo', 'bar', 'baz'], 
			['baz', 'foo', 'bar'], 
			['bar', 'foo', 'baz'],
			['foo', 'baz', 'bar'],
			['bar', 'baz', 'foo'],
			['baz', 'bar', 'foo']
		];
		
		$actual = Arr::permute($array);
		
		// reduce $expected and $actual to single-dimensional arrays so we can use
		//     array_diff to compare (because the elements will be out of order)
		//
		$expected = array_map(function ($v) {
			return implode('', $v);
		}, $expected);
		
		$actual = array_map(function ($v) {
			return implode('', $v);
		}, $actual);
		
		$this->assertEquals(0, count(array_diff($expected, $actual)));
		
		return;
	}
	
	
	/* !sortByField() */
	
	/**
	 * sortByField() should throw a BadMethodCallException if array and field are null
	 */
	public function testSortByField_throwsBadMethodCallException_ifArgumentsAreNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Arr::sortByField(null, null);
		
		return;
	} 
	
	/**
	 * sortByField() should throw an InvalidArgumentException if array is not an array
	 *
	 * @dataProvider provideNonArrayValues
	 */
	public function testSortByField_throwsInvalidArgumentException_ifInputArrayIsNotAnArray($array)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByField($array, 'foo');
		
		return;	
	}
	
	/**
	 * sortByField() should throw an InvalidArgumentException if field is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testSortByField_throwsInvalidArgumentException_ifFieldIsNotAString($field)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByField(array(), $field);
		
		return;
	}
	
	/**
	 * sortByField() should throw an InvalidArgumentException if sort is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testSortByField_throwsInvalidArgumentException_ifSortIsNotAString($sort) 
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByField(array(), 'foo', $sort);
		
		return;
	}
	
	/**
	 * sortByField() should throw an InvalidArgumentException if sort is not 'asc[ending]'
	 *     or 'desc[ending']
	 */
	public function testSortByField_throwsInvalidArgumentException_ifSortIsNotValid()
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByField(array(), 'foo', 'bar');
		
		return;
	}
	
	/**
	 * sortByField() should throw an InvalidArgumentException if $array is not an array
	 *     of arrays
	 */
	public function testSortByField_throwsInvalidArgumentException_ifArrayIsNotArrays()
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByField(array('foo', 'bar', 'baz'), 'qux');
		
		return;
	}
	
	/**
	 * sortByField() should throw an InvalidArgumentException if $array is not an array of
	 *     arrays with field $field
	 */
	public function testSortByField_throwsInvalidArgumentException_ifFieldDoesNotExist()
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByField(array(
			array('foo' => 'bar'),
			array('foo' => 'baz'),
			array('foo' => 'qux'),
		), 'quux');
		
		return;
	}
	
	/**
	 * sortByField() should sort an array in descending order
	 */
	public function testSortByField_returnsSortedArray_ifFieldExists()
	{
		$array = array(
			array('foo' => 2),
			array('foo' => 3),
			array('foo' => 1)
		);
		$expected = array(
			array('foo' => 1),
			array('foo' => 2),
			array('foo' => 3)
		);
		$actual = Arr::sortByField($array, 'foo');
		
		return $this->assertEquals($actual, $expected);
	}
	
	
	/* !sortByMethod() */
	
	/**
	 * sortByMethod() should throw a BadMethodCallException if array and field are null
	 */
	public function testSortByMethod_throwsBadMethodCallException_ifArgumentsAreNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Arr::sortByMethod(null, null);
		
		return;
	} 
	
	/**
	 * sortByMethod() should throw an InvalidArgumentException if array is not an array
	 *
	 * @dataProvider provideNonArrayValues
	 */
	public function testSortByMethod_throwsInvalidArgumentException_ifArrayIsNotAnArray($array)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByMethod($array, 'foo');
		
		return;	
	}
	
	/**
	 * sortByMethod() should throw an InvalidArgumentException if $method is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testSortByMethod_throwsInvalidArgumentException_ifMethodIsNotAString($method)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByMethod(array(), $method);
		
		return;
	}
	
	/**
	 * sortByMethod() should throw an InvalidArgumentException if sort is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testSortByMethod_throwsInvalidArgumentException_ifSortIsNotAString($sort) 
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByMethod(array(), 'foo', $sort);
		
		return;
	}
	
	/**
	 * sortByMethod() should throw an InvalidArgumentException if sort is not 'asc[ending]'
	 *     or 'desc[ending']
	 */
	public function testSortByMethod_throwsInvalidArgumentException_ifSortIsNotValid()
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByMethod(array(), 'foo', 'bar');
		
		return;
	}
	
	/**
	 * sortByMethod() should throw an InvalidArgumentException if $array is not an array
	 *     of objects
	 */
	public function testSortByMethod_throwsInvalidArgumentException_ifArrayIsNotObjects()
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByMethod(array('foo', 'bar', 'baz'), 'qux');
		
		return;
	}
	
	/**
	 * sortByMethod() should throw an InvalidArgumentException if $array is not an array of
	 *     objects with the callable method $method
	 */
	public function testSortByMethod_throwsInvalidArgumentException_ifMethodIsNotCallable()
	{		
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByMethod(array(new StdClass(), new StdClass(), new StdClass()), 'foo');
		
		return;
	}
	
	/**
	 * sortByMethod() should sort an array in descending order
	 */
	public function testSortByMethod_returnsSortedArray_ifMethodIsCallable()
	{		
		$array = array(new Foo(2), new Foo(3), new Foo(1));
		$actual = Arr::sortByMethod($array, 'bar');
		
		$this->assertEquals($actual[0]->bar(), 1);
		$this->assertEquals($actual[1]->bar(), 2);
		$this->assertEquals($actual[2]->bar(), 3);
		
		return;
	}
	
	
	/* !sortByProperty() */
	
	/**
	 * sortByProperty() should throw a BadMethodCallException if array and field are null
	 */
	public function testSortByProperty_throwsBadMethodCallException_ifArgumentsAreNull()
	{
		$this->setExpectedException('BadMethodCallException');
		Arr::sortByProperty(null, null);
		
		return;
	} 
	
	/**
	 * sortByProperty() should throw an InvalidArgumentException if $array is not an array
	 *
	 * @dataProvider provideNonArrayValues
	 */
	public function testSortByProperty_throwsInvalidArgumentException_ifArrayIsNotAnArray($array)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByProperty($array, 'foo');
		
		return;	
	}
	
	/**
	 * sortByProperty() should throw an InvalidArgumentException if $property is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testSortByProperty_throwsInvalidArgumentException_ifMethodIsNotAString($property)
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByProperty(array(), $property);
		
		return;
	}
	
	/**
	 * sortByProperty() should throw an InvalidArgumentException if sort is not a string
	 *
	 * @dataProvider provideNonStringValues
	 */
	public function testSortByProperty_throwsInvalidArgumentException_ifSortIsNotAString($sort) 
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByProperty(array(), 'foo', $sort);
		
		return;
	}
	
	/**
	 * sortByProperty() should throw an InvalidArgumentException if sort is not 'asc[ending]'
	 *     or 'desc[ending']
	 */
	public function testSortByProperty_throwsInvalidArgumentException_ifSortIsNotValid()
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByProperty(array(), 'foo', 'bar');
		
		return;
	}
	
	/**
	 * sortByProperty() should throw an InvalidArgumentException if $array is not an array
	 *     of objects
	 */
	public function testSortByProperty_throwsInvalidArgumentException_ifArrayIsNotObjects()
	{
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByProperty(array('foo', 'bar', 'baz'), 'qux');
		
		return;
	}
	
	/**
	 * sortByProperty() should throw an InvalidArgumentException if $array is not an array of
	 *     objects with the public property $property
	 */
	public function testSortByProperty_throwsInvalidArgumentException_ifPropertyIsNotCallable()
	{		
		$this->setExpectedException('InvalidArgumentException');
		Arr::sortByProperty(array(new StdClass(), new StdClass(), new StdClass()), 'foo');
		
		return;
	}
	
	/**
	 * sortByProperty() should sort an array in descending order
	 */
	public function testSortByProperty_returnsSortedArray_ifPropertyIsCallable()
	{		
		$array = array(new Foo(2), new Foo(3), new Foo(1));
		$actual = Arr::sortByMethod($array, 'bar');
		
		$this->assertEquals($actual[0]->bar, 1);
		$this->assertEquals($actual[1]->bar, 2);
		$this->assertEquals($actual[2]->bar, 3);
		
		return;
	}
}
