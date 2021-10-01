<?php

namespace Jstewmc\PhpHelpers;

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

class ArrTest extends \PHPUnit\Framework\TestCase
{
	public function testDiffReturnsArrayWhenDiffIsInsert(): void
	{
		$from = ['foo'];
		$to   = ['foo', 'bar'];

		$expected = [['value' => 'foo', 'mask' => 0], ['value' => 'bar', 'mask' => 1]];
		$actual   = Arr::diff($from, $to);

		$this->assertEquals($expected, $actual);
	}

	public function testDiffReturnsArrayWhenDiffIsDelete(): void
	{
		$from = ['foo', 'bar'];
		$to   = ['foo'];

		$expected = [['value' => 'foo', 'mask' => 0], ['value' => 'bar', 'mask' => -1]];
		$actual   = Arr::diff($from, $to);

		$this->assertEquals($expected, $actual);
	}

	public function testDiffReturnsArrayWhenDiffIsUpdate(): void
	{
		$from = ['foo'];
		$to   = ['bar'];

		$expected = [['value' => 'foo', 'mask' => -1], ['value' => 'bar', 'mask' => 1]];
		$actual   = Arr::diff($from, $to);

		$this->assertEquals($expected, $actual);
	}

	public function testFilterByKeyReturnsEmptyArrayWhenInputArrayIsEmpty(): void
	{
		$result = Arr::filterBykey([], function () { return true; });

		$this->assertEquals([], $result);
	}

	public function testFilterByKeyReturnsEmptyArrayWhenKeysDoNotMatch(): void
	{
		$result = Arr::filterByKey(['foo' => 'bar'], function () { return false; });

		$this->assertEquals([], $result);
	}

	public function testFilterByKeyReturnsArrayWhenKeysDoMatch(): void
	{
		$input    = ['foo' => 'bar', 'baz' => 'qux', 'quux' => 'corge'];
		$expected = ['foo' => 'bar', 'baz' => 'qux'];
		$actual   = Arr::filterByKey($input, function ($k) {
			return in_array($k, ['foo', 'baz']);
		});

		$this->assertEquals($expected, $actual);
	}

	public function testFilterByKeyPrefixReturnsEmptyArrayWhenInputArrayIsEmpty(): void
	{
		$result = Arr::filterByKeyPrefix([], 'foo');

		$this->assertEquals([], $result);
	}

	public function testFilterByKeyPrefixReturnsEmptyArrayWhenKeysDoNotMatch(): void
	{
		$result = Arr::filterByKeyPrefix(['foo' => 'bar', 'baz' => 'qux'], 'quux');

		$this->assertEquals([], $result);
	}

	public function testFilterByKeyPrefixReturnsArrayWhenKeysDoMatch(): void
	{
		$input    = ['foo' => 'bar', 'baz' => 'qux', 'quux' => 'corge'];
		$actual   = Arr::filterByKeyPrefix($input, 'f');
		$expected = ['foo' => 'bar'];

		$this->assertEquals($expected, $actual);
	}

	public function testInArrayReturnsFalseWhenInputArrayIsEmpty(): void
	{
		$this->assertFalse(Arr::inArray('foo*', []));
	}

	public function testInArrayReturnsFalseWhenMatchDoesNotExist(): void
	{
		$this->assertFalse(Arr::inArray('q*', ['foo', 'bar', 'baz']));
	}

	public function testInArrayReturnsTrueWhenExactMatchExists(): void
	{
		$this->assertTrue(Arr::inArray('foo', ['foo', 'bar', 'baz']));
	}

	public function testInArrayReturnsTrueWhenBeginsWithMatchExists(): void
	{
		$this->assertTrue(Arr::inArray('f*', ['foo', 'bar', 'baz']));
	}

	public function testInArrayReturnsTrueWhenEndsWithMatchExists(): void
	{
		$this->assertTrue(Arr::inArray('*z', ['foo', 'bar', 'baz']));
	}

	public function testInArrayReturnsTrueWhenContainsMatchExists(): void
	{
		$this->assertTrue(Arr::inArray('*a*', ['foo', 'bar', 'baz']));
	}

	public function testIsAssocReturnsFalseWhenArrayIsNull(): void
	{
		$this->assertFalse(Arr::isAssoc(null));
	}

	public function testIsAssocReturnsFalseWhenArrayIsNotAnArray(): void
	{
		$this->assertFalse(Arr::isAssoc(1));
	}

	public function testIsAssocReturnsFalseWhenArrayDoesNotHaveStringKey(): void
	{
		// PHP will cast int strings to integers
		$this->assertFalse(Arr::isAssoc([0 => 'foo', '1' => 'bar']));
	}

	public function testIsAssocReturnsTrueWhenArrayHasStringKey(): void
	{
		$this->assertTrue(Arr::isAssoc(['foo' => 'bar', 2 => 'baz']));
	}

	public function testIsEmptyReturnsTrueWhenInputArrayIsEmpty(): void
	{
		$this->assertTrue(Arr::isEmpty('foo', []));
	}

	public function testIsEmptyReturnsTrueWhenKeyDoesNotExist(): void
	{
		$this->assertTrue(Arr::isEmpty('foo', ['bar' => true]));
	}

	public function testIsEmptyReturnsTrueWhenValueIsEmpty(): void
	{
		$this->assertTrue(Arr::isEmpty('foo', ['foo' => null]));
	}

	public function testIsEmptyReturnFalseWhenValueIsNotEmpty(): void
	{
		$this->assertFalse(Arr::isEmpty('foo', ['foo' => true]));
	}

	public function testIsEmptyReturnFalseWhenValueIsZeroAndZeroIsNotEmpty(): void
	{
		$this->assertFalse(Arr::isEmpty('foo', ['foo' => 0], false));
	}

	public function testKeyStringReplaceReturnsEmptyArrayWhenInputArrayIsEmpty(): void
	{
		$this->assertEquals([], Arr::keyStringReplace('foo', 'bar', []));
	}

	public function testKeyStringReplaceReturnsArrayWhenKeysDoNotMatch(): void
	{
		$array    = ['baz' => 'qux', 'quux' => 'corge'];
		$actual   = Arr::keyStringReplace('foo', 'bar', $array);
		$expected = $array;

		$this->assertEquals($expected, $actual);
	}

	public function testKeyStringReplaceReturnsArrayWhenKeysDoMatch(): void
	{
		$array    = ['foo' => 'bar', 'foobar' => 'baz', 'qux' => 'quux'];
		$actual   = Arr::keyStringReplace('foo', 'bar', $array);
		$expected = ['bar' => 'bar', 'barbar' => 'baz', 'qux' => 'quux'];

		$this->assertEquals($expected, $actual);
	}

	public function testPermuteReturnsArrayWhenTwoElementsExist(): void
	{
		$array = ['foo', 'bar'];

		$expected = [['foo', 'bar'], ['bar', 'foo']];
		$actual   = Arr::permute($array);

		$this->assertEqualsCanonicalizing($expected, $actual);
	}

	public function testPermuteReturnsArrayWhenThreeElementsExist(): void
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

		$this->assertEqualsCanonicalizing($expected, $actual);
	}

	public function testSortByFieldThrowsInvalidArgumentExceptionWhenSortIsInvalid(): void
	{
		$this->expectException(\InvalidArgumentException::class);

		Arr::sortByField([], 'foo', 'bar');
	}

	public function testSortByFieldThrowsInvalidArgumentExceptionWhenArrayIsNotArrays(): void
	{
		$this->expectException(\InvalidArgumentException::class);

		Arr::sortByField(['foo', 'bar', 'baz'], 'qux');
	}

	public function testSortByFieldThrowsInvalidArgumentExceptionWhenFieldDoesNotExist(): void
	{
		$this->expectException(\InvalidArgumentException::class);

		Arr::sortByField(array(
			['foo' => 'bar'],
			['foo' => 'baz'],
			['foo' => 'qux'],
		), 'quux');
	}

	public function testSortByFieldReturnsSortedArrayWhenFieldExists(): void
	{
		$array = [
			['foo' => 2],
			['foo' => 3],
			['foo' => 1]
		];

		$expected = [
			['foo' => 1],
			['foo' => 2],
			['foo' => 3]
		];

		$actual = Arr::sortByField($array, 'foo');

		$this->assertEquals($actual, $expected);
	}

	public function testSortByMethodThrowsInvalidArgumentExceptionWhenSortIsInvalid(): void
	{
		$this->expectException(\InvalidArgumentException::class);

		Arr::sortByMethod([], 'foo', 'bar');
	}

	public function testSortByMethodThrowsInvalidArgumentExceptionWhenArrayIsNotObjects(): void
	{
		$this->expectException(\InvalidArgumentException::class);

		Arr::sortByMethod(['foo'], 'qux');
	}

	public function testSortByMethodThrowsInvalidArgumentExceptionWhenMethodIsNotCallable(): void
	{
		$this->expectException(\InvalidArgumentException::class);

		Arr::sortByMethod([new \StdClass(), new \StdClass(), new \StdClass()], 'foo');
	}

	public function testSortByMethodReturnsSortedArrayWhenMethodIsCallable(): void
	{
		$input = [new Foo(2), new Foo(3), new Foo(1)];

		$expected = [new Foo(1), new Foo(2), new Foo(3)];
		$actual = Arr::sortByMethod($input, 'bar');

		$this->assertEquals($expected, $actual);
	}

	public function testSortByPropertyThrowsInvalidArgumentExceptionWhenSortIsInvalid(): void
	{
		$this->expectException(\InvalidArgumentException::class);

		Arr::sortByProperty([], 'foo', 'bar');
	}

	public function testSortByPropertyThrowsInvalidArgumentExceptionWhenArrayIsNotObjects(): void
	{
		$this->expectException(\InvalidArgumentException::class);

		Arr::sortByProperty(['foo', 'bar', 'baz'], 'qux');
	}

	public function testSortByPropertyThrowsInvalidArgumentExceptionWhenPropertyIsNotCallable(): void
	{
		$this->expectException(\InvalidArgumentException::class);

		Arr::sortByProperty([new \StdClass(), new \StdClass(), new \StdClass()], 'foo');
	}

	public function testSortByPropertyReturnsSortedArrayWhenPropertyIsCallable(): void
	{
		$array = [new Foo(2), new Foo(3), new Foo(1)];

		$expected = [new Foo(1), new Foo(2), new Foo(3)];
		$actual = Arr::sortByMethod($array, 'bar');

		$this->assertEquals($expected, $actual);
	}
}
