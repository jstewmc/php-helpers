# PHP Helpers
Static classes to help with PHP strings, arrays, numbers, files, and bools.

Static helper classes are nothing new in PHP. In fact, most of these functions have probably been written dozens of times in better libraries than mine. However, I wrote (or copied these functions from the web with credit) when I worked on a project that required as few dependencies as possible. I figured they were a great candidate for my first GitHub repository.

Feel free to check out the [API documentation](https://jstewmc.github.io/php-helpers), [report an issue](https://github.com/jstewmc/php-helpers/issues), [contribute](https://github.com/jstewmc/php-helpers/blob/master/contributing.md), or [ask a question](mailto:clayjs0@gmail.com). 

## Examples
Here are examples for the most commonly used functions.

### Numbers (aka, "Num")
```php
// evaluate integers, floats, fractions, mixed numbers, or comma-separated values
// PHP doesn't like fractions (e.g., '1/2') or mixed numbers (e.g., '1 1/2')
//
Num::val('1/2');    // returns (float) 0.5
Num::val('1,000');  // returns (int) 1000

// round, ceil, or floor a number to the nearest multiple of another number
// e.g., round/ceil/floor 7 to the nearest multiple of 10
//
Num::roundTo(7, 10);  // returns 10
Num::ceilTo(7, 10);   // returns 10
Num::floorTo(7, 10);  // returns 0

// test whether or not a number is an integer
// PHP's is_int() returns false on integer strings
//
Num::isInt('1,000');  // returns true
Num::isInt(1000);     // returns true

// test whether or not a number is a database id
// database ids must be integers and are subject to size limits
//
Num::isId(1, 'tinyint');    // returns true
Num::isId(999, 'tinyint');  // returns false

// test whether or not a number is zero
// in loosely-typed languages like PHP, zero can be many things
//
Num::isZero('0');    // returns true
Num::isZero(false);  // returns false

// test floats for equality
// because of the way floats are stored in memory, you shouldn't compare them
//     directly using the "==" or "===" operator
//
Num::almostEqual(0.2, 0.2);  // returns true
Num::almostEqual(0.2, 0.3);  // returns false
```

### Strings (aka, "Str")
``` php
// create a random string using 'lower', 'upper', 'alpha' (lower + alpha), 
//     'number', or 'symbol' character sets
//
Str::rand(8);                       // returns string like '9#hb%Fv3'
Str::rand(8, ['upper', 'number']);  // returns string like 'P9K7HG32'

// create a random string with requirements (aka, a password)
// you can specify the minimum number of characters that must be present
//     from the 'lower', 'upper', 'alpha' (lower + upper), 'number', or
//     'symbol' charsets in the string
//
Str::password(8);                   // returns string like 'jNb^3#L@'
Str::password(8, ['upper' => 8]);   // returns string like 'NBDRATCV'
Str::password(8, ['number' => 8]);  // returns string like '98643109'

// truncate a string to the desired length (neatly)
// for the record, the 10-th character is the "u" in "ipsum" and the 15-th 
//     character is the "u" in "inum"
//
Str::truncate('Lorem ipsum inum', 10);  // returns 'Lorem...'
Str::truncate('Lorem ipsum inum', 15);  // returns 'Lorem ipsum...'
Str::truncate('Lorem ipsum inum', 99);  // returns 'Lorem ipsum inum'

// test whether a string starts or ends with the given substring
// both case-sensitive and case-insensitive versions are available
//
Str::endsWith('foo', 'o');     // returns true
Str::iEndsWith('foo', 'O')     // returns true
Str::startsWith('foo', 'f');   // returns true
Str::iStartsWith('foo', 'F');  // returns true

// split a string on the first alpha
Str::splitOnFirstAlpha('123 foo');  // returns ['123', 'foo']
Str::splitOnFirstAlpha('123');      // returns ['123']
Str::splitOnFirstAlpha('foo');      // returns ['foo']
```

### Booleans (aka, "Bool")
``` php
// convert a bool value to it's string equivalent
// supports "true/false", "yes/no", and "on/off" 
//
Bool::booltostr(true, 'yes/no');  // prints 'yes'

// evaluates any value to its boolean equivalent
// PHP's boolval() doesn't support "yes/no" or "on/off" strings
//
Bool::val('on');  // prints true
Bool::val('no');  // prints false
```

### Arrays (aka, "Arr")

Filtering functions:

```php
// filter an array by key or key prefix
$a = array('foo' => 1, 'bar' => 2, 'baz' => 3);

Arr::filterByKeyPrefix($a, 'b');      // returns ['bar' => 2, 'baz' => 2]
Arr::filterByKey($a, function ($k) { 
	return substr($k, 0, 1) === 'b';  
});                                   // returns ['bar' => 2, 'baz' => 2]
```

Sorting functions:

```php
// sort an array by a field's value
$a = [['foo' => 2], ['foo' => 3], ['foo' => 1]];

Arr::sortByField($a, 'foo');          // returns [['foo' => 1], ['foo' => 2], ['foo' => 3]]
Arr::sortByField($a, 'foo', 'desc');  // returns [['foo' => 3], ['foo' => 2], ['foo' => 1]]
```

```php
// sort an array of objects by property or method
class Foo
{
	public $bar;
	
	public function __construct($bar) 
	{
		$this->bar = $bar;
	}
	
	public function getBar()
	{
		return $this->bar;
	}
}

$a = [new Foo(2), new Foo(3), new Foo(1)];

Arr::sortByProperty($a, 'foo');   // returns (psuedo) [{bar: 1}, {bar: 2}, {bar: 3}]
Arr::sortByMethod($a, 'getBar');  // returns (psuedo) [{bar: 1}, {bar: 2}, {bar: 3}]
```

Other array functions:

```php
// wildcard in_array()
$a = ['foo', 'bar', 'baz'];

Arr::inArray($a, 'f*');   // returns true
Arr::inArray($a, '*z');   // returns true
Arr::inArray($a, '*a*');  // returns true
```

```php
// is the array associative?
$a = [0 => 'foo', 1 => 'bar']; 
$b = [0 => 'foo', 1 => 'bar', 'baz' => 'qux'];

Arr::isAssoc($a);  // returns false
Arr::isAssoc($b);  // returns true
```

```php
// is the key-value empty?
$a = ['foo' => null, 'bar' => array(), 'baz' => 1];

Arr::isEmpty('qux', $a);  // returns true
Arr::isEmpty('foo', $a);  // returns true
Arr::isEmpty('bar', $b);  // returns true
Arr::isEmpty('baz', $b);  // returns false
```

```php
// str_ireplace() for array keys
$a = ['foo' => 'bar', 'baz' => 'qux'];
Arr::keyStringReplace('f', 'g', $a);  // returns ['goo' => 'bar', 'baz' => 'qux'];
Arr::keyStringReplace('f', '', $a);   // returns ['oo' => 'bar', 'baz' => 'qux'];
```

### Directories (aka, "Dir")

```php
// copy a non-empty directory
// PHP's copy() method will not work with non-empty directories
//
// this example assumes a directory "foo" exists in the current directory
// if the destination directory doesn't exist, it will be created
//
$source = dirname(__FILE__).DIRECTORY_SEPARATOR.'foo';
$destination = dirname(__FILE__).DIRECTORY_SEPARATOR.'bar';

Dir::copy($source, $destination);  // returns true
```

```php
// remove a non-empty directory
// PHP's rmdir() method will not work with non-empty directories
//
// this example assumes a directory "foo" exists in the current directory
//
$directory = dirname(__FILE__).DIRECTORY_SEPARATOR.'foo';

Dir::remove($directory);  // returns true
```

## Tests

This is my first attempt at a full suite of unit tests. 

## Contributing

Feel free to contribute your own improvements:

1. Fork
2. Clone
3. PHPUnit
4. Branch
5. PHPUnit
6. Code
7. PHPUnit
8. Commit
9. Push
10. Pull request
11. Relax and eat a Paleo muffin

See [contributing.md](https://github.com/jstewmc/php-helpers/blob/master/contributing.md) for details.

## Author

Jack Clayton - [clayjs0@gmail.com](mailto:clayjs0@gmail.com).

## License

PhpHelpers is released under the MIT License. See the [LICENSE](https://github.com/jstewmc/php-helpers/blob/master/LICENSE) file for details.

## History

You can view the history of the PhpHelpers project in the [changelog.md](https://github.com/jstewmc/php-helpers/blob/master/changelog.md) file.
