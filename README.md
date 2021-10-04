[![CircleCI](https://circleci.com/gh/jstewmc/php-helpers.svg?style=svg)](https://circleci.com/gh/jstewmc/php-helpers) [![codecov](https://codecov.io/gh/jstewmc/php-helpers/branch/master/graph/badge.svg?token=nO0rvmLWUk)](https://codecov.io/gh/jstewmc/php-helpers)

# PHP Helpers
Static classes to help with PHP strings, arrays, numbers, files, and boolean values.

Static helper classes are nothing new in PHP. In fact, most of these functions have probably been written dozens of times in better libraries than mine. However, I wrote (or copied these functions from the web with credit) when I worked on a project that required as few dependencies as possible, and I figured I would share them.

## Installation

This library requires [PHP 7.4+](https://secure.php.net).

It is multi-platform, and we strive to make it run equally well on Windows, Linux, and OSX.

It should be installed via [Composer](https://getcomposer.org). To do so, add the following line to the `require` section of your `composer.json` file, and run `composer update`:

```javascript
{
   "require": {
       "jstewmc/php-helpers": "^0.2"
   }
}
```

## Usage

Here are examples for the most commonly used functions.

(Please note, I've omitted the requisite `use Jstewmc\PhpHelpers\{Arr, Boolean, Dir, Num, Str};` statements to keep the examples cleaner.)

### Numbers (aka, "Num")

You can use `val()` method to evaluate integers, floats, fractions (e.g., `"1/2"`), mixed numbers (e.g., `"1 1/2"`), comma-separated values (e.g., `"1,000"`), and english-worded numbers (e.g., `"two hundred and fifty-six"`) to their numeric equivalent:

```php
Num::val('1/2');          // returns (float) 0.5
Num::val('1,000');        // returns (int) 1000
Num::val('one hundred');  // returns (int) 100

Num::val('two million, ninety-seven thousand, one hundred and fifty-two');  
// returns (int) 2,097,152
```

You can use the `*To()` methods to round, ceil, or floor a number to the nearest multiple of another number:

```php
Num::roundTo(7, 10);  // returns 10
Num::ceilTo(7, 10);   // returns 10
Num::floorTo(7, 10);  // returns 0
```

You can use the `bound()` method to keep a number greater than or equal to a lower bound, less than or equal to an upper bound, or both:

```php
Num::bound(1, 10);  
// returns 10, because 1, the number, is less than 10, the lower bound

Num::bound(10, 1, 5);
// returns 5, because 10, the number, is greater than 5, the upper bound
```

You can use the `normalize()` method to index a number according to a maximum value:

```php
Num::normalize(1, 10);   // returns 0.1
Num::normalize(5, 10);   // returns 0.5
Num::normalize(10, 10);  // returns 1
```

You can use the `isNumeric()` method to test whether or not a value is a number, including fractions, mixed numbers, and english phrases:

```php
Num::isNumeric(1);             // returns true
Num::isNumeric('1/2');         // returns true
Num::isNumeric('1 1/2');       // returns true
Num::isNumeric('one hundred'); // returns true
Num::isNumeric('foo');         // returns false
```

You can use the `isInt()` method to test whether or not a number or string is an integer:

```php
Num::isInt('1,000');  // returns true
Num::isInt(1000);     // returns true
```

You can use the `isId()` method to test whether or not a number is a valid database identifier (i.e., a positive integer, optionally of the correct datatype size):

```php
Num::isId('foo');           // returns false
Num::isId(1.5);             // returns false
Num::isId(1);               // returns true
Num::isId(1, 'tinyint');    // returns true
Num::isId(999, 'tinyint');  // returns false (too big)
```

You can use the `isZero()` method to test whether or not a value is zero (in loosely-typed languages like PHP, zero can be many things):

```php
Num::isZero(0);      // returns true
Num::isZero('0');    // returns true
Num::isZero(false);  // returns false
```

You can use the `almostEqual()` method to test whether or not two floats are "equal" (because of the way floats are stored in memory, you shouldn't compare them directly using the `==` or `===` operators):

```php
Num::almostEqual(0.2, 0.2);  // returns true
Num::almostEqual(0.2, 0.3);  // returns false
```

### Strings (aka, "Str")

You can use `rand()` to generate a random string of a given length, optionally with specific latin character sets (allowed character sets are `'lower'`, `'upper'`, `'alpha'` (a shortcut for `'lower' + 'upper'`), `number`, or `symbol`):

``` php
Str::rand(8);                       // returns a string like '9#hb%Fv3'
Str::rand(8, ['upper', 'number']);  // returns a string like 'P9K7HG32'
```

You can use `password()` to generate a random string, optionally with the minimum number of characters that must be present from latin character sets:

```php
Str::password(8);
// returns a string like 'jNb^3#L@'

Str::password(8, ['upper' => 8]);   
// returns a string like 'NBDRATCV', with exactly eight upper-case characters

Str::password(16, ['number' => 8]);  
// returns a string like '*9f8F6b4F3f1:0/9', with at least eight numbers
```

You can use `truncate()` to neatly cut a string at or near the desired length (by default, the break character is the space character (`' '`) and the padding is an ellipsis (`'...'`)):

```php
Str::truncate('Lorem ipsum inum', 10);  
// returns 'Lorem...', because the "u" in "ipsum" is the 10th character and
// the space after "Lorem" is the closest break character

Str::truncate('Lorem ipsum inum', 15);  
// returns 'Lorem ipsum...', because the "u" in "inum" is the 15th character

Str::truncate('Lorem ipsum inum', 99);  
// returns 'Lorem ipsum inum', because the string is shorter than the limit
```

You can use the `*With()` methods to determine whether or not a string starts or ends with a given substring:

```php
Str::endsWith('foo', 'o');     // returns true
Str::iEndsWith('foo', 'O')     // returns true
Str::startsWith('foo', 'f');   // returns true
Str::iStartsWith('foo', 'F');  // returns true
```

You can use the `strtocamelcase()` method to convert a string to camel-case:

```php
Str::strtocamelcase('Hello world');   // returns "helloWorld"
Str::strtocamelcase('H3LLO WORLD!');  // returns "helloWorld"
Str::strtocamelcase('hello_world');   // returns "helloWorld"
```

You can use the `splitOnFirstAlpha()` method to split a string on the first alphabetical character:

```php
Str::splitOnFirstAlpha('123 foo');  // returns ['123', 'foo']
Str::splitOnFirstAlpha('123');      // returns ['123']
Str::splitOnFirstAlpha('foo');      // returns ['foo']
```

You can use the `strtobytes()` method to convert an `.ini`-style byte string (e.g., `'1G'`) to a number:

```php
Str::strtobytes('1K');  // returns 1024
Str::strtobytes('1M');  // returns 1,048,576
```

You can use the `isBool()` method to determine whether or not a string is a "bool-ish" value:

```php
Str::isBool('foo');  // returns false
Str::isBool('yes');  // returns true
```

### Booleans

You can use the `booltostr()` method to convert a boolean value to a string:

``` php
Boolean::booltostr(true, 'yes/no');      // returns 'yes'
Boolean::booltostr(true, 'true/false');  // returns 'true'
Boolean::booltostr(true, 'on/off');      // returns 'on'
```

You can use the `val()` method to evaluate a "bool-ish" value to its boolean equivalent (PHP's native `boolval()` method doesn't support `"yes"`/`"no"` or `"on"`/`"off"` strings):

```php
Boolean::val(true);   // returns true
Boolean::val('on');   // returns true
Boolean::val('yes');  // returns true
```

### Arrays (aka, "Arr")

You can use the `filterByKeyPrefix()` to filter an array by a string prefix:

```php
$array = ['foo' => 1, 'bar' => 2, 'baz' => 3];

Arr::filterByKeyPrefix($array, 'b');  // returns ['bar' => 2, 'baz' => 2]
```

You can use the `filterByKey()` method to filter an array by key using a custom function:

```php
$array = ['foo' => 1, 'bar' => 2, 'baz' => 3];

Arr::filterByKey($a, function ($k) {
	return substr($k, 0, 1) === 'b';  
});
// returns ['bar' => 2, 'baz' => 2]
```

You can use the `sortByField()` method to sort an array of _associative arrays_ in ascending or descending order:

```php
$arrays = [['foo' => 2], ['foo' => 3], ['foo' => 1]];

Arr::sortByField($arrays, 'foo');          
// returns [['foo' => 1], ['foo' => 2], ['foo' => 3]]

Arr::sortByField($arrays, 'foo', 'desc');  
// returns [['foo' => 3], ['foo' => 2], ['foo' => 1]]
```

You can use the `sortByProperty()` or `sortByMethod()` methods to sort an array of _objects_ in ascending or descending order, using a property or method, respectively:

```php
// define a example class (for the purposes of this example, we'll define both a
// public property and a getter method)
class Example
{
	public $property;

	public function __construct(int $value)
	{
		$this->property = $value;
	}

	public function getProperty(): int
	{
		return $this->property;
	}
}

$objects = [new Example(2), new Example(3), new Example(1)];

Arr::sortByProperty($objects, 'foo');  
// returns (in pseudo-code) [{bar: 1}, {bar: 2}, {bar: 3}]

Arr::sortByMethod($objects, 'getBar');  
// returns (in pseudo-code) [{bar: 1}, {bar: 2}, {bar: 3}]
```

You can use the `inArray()` method to search for values in an array using wildcard notation (by default, the wildcard character is the asterisk character (`"*"`)):

```php
$values = ['foo', 'bar', 'baz'];

Arr::inArray($values, 'f*');   // returns true, because of the leading "f" in "foo"
Arr::inArray($values, '*z');   // returns true, because of the trailing "z" in "baz"
Arr::inArray($values, '*a*');  // returns true, because of the "a" in "bar" and "baz"
```

You can use the `diff()` method to determine the [Levenshtein Distance](https://en.wikipedia.org/wiki/Levenshtein_distance), the number of single-element edits required to change one array into another) between two arrays:

```php
$array1 = ['foo', 'bar', 'baz'];
$array2 = ['bar', 'qux'];

$actual = Arr::diff($array1, $array2);

$expected = [
	['value' => 'foo', 'mask' => -1],  // because "foo" should be deleted
	['value' => 'bar', 'mask' => 0],   // because "bar" should be unchanged
	['value' => 'baz', 'mask' => -1],  // because "baz" should be deleted
	['value' => 'qux', 'mask' => 1]    // because "qux" should be added
];

$actual == $expected; // returns true
```

You can use the `permute()` method to calculate an array's permutations (careful, the number of permutations grows as a factorial of the size of the original array):

```php
$array = ['foo', 'bar', 'baz'];

$actual = Arr::permute($array);

$expected = [
	['foo', 'bar', 'baz'],
	['baz', 'foo', 'bar'],
	['bar', 'foo', 'baz'],
	['foo', 'baz', 'bar'],
	['bar', 'baz', 'foo'],
	['baz', 'bar', 'foo']
];

$actual == $expected;  // returns true
```

You can use the `isAssoc()` method to determine whether or not an array is associative (i.e., has a string key):

```php
$array1 = [0 => 'foo', 1 => 'bar'];
$array2 = [0 => 'foo', 1 => 'bar', 'baz' => 'qux'];

Arr::isAssoc($array1);  // returns false
Arr::isAssoc($array2);  // returns true, because there is a string key
```

You can use the `isEmpty()` method to determine whether or not a key exists in an array with a non-empty value:

```php
$values = ['foo' => null, 'bar' => [], 'baz' => 1];

Arr::isEmpty('qux', $values);  
// returns true, because the key "qux" does not exist

Arr::isEmpty('foo', $values);  
// returns true, because the value of key "foo" is null

Arr::isEmpty('bar', $values);  
// returns true, because the value of key "bar" is empty array

Arr::isEmpty('baz', $values);  
// returns false, because the value of key "baz" is not empty
```

You can use the `keyStringReplace()` method to replace substrings in array keys:

```php
$array = ['foo' => 'bar', 'baz' => 'qux'];

Arr::keyStringReplace('f', 'g', $a);
// returns ['goo' => 'bar', 'baz' => 'qux'], because "f" was replaced with "g"

Arr::keyStringReplace('f', '', $a);   
// returns ['oo' => 'bar', 'baz' => 'qux'], because "f" was replaced with ""
```

### Directories (aka, "Dir")

You can use the `copy()` method to duplicate a non-empty directory (PHP's native `copy()` method will not work with non-empty directories):

```php
$source = dirname(__FILE__).'/foo';
$destination = dirname(__FILE__).'/bar';

Dir::copy($source, $destination);  // returns true
```

You can use the `remove()` method to delete a non-empty directory (PHP's native `rmdir()` method will not work with non-empty directories):

```php
$directory = dirname(__FILE__).'/foo';

// The $container verifies you don't delete a directory outside of the target
// area accidentally. The path of the directory to be deleted MUST start with
// this path.
$container = dirname(__FILE__);  

Dir::remove($directory, $container);  // returns true
```

You can use the `abs2rel()` method to convert an absolute path name to a relative one:

```php
Dir::abs2rel('/path/to/foo/bar/baz', '/path/to');  // returns "foo/bar/baz"
Dir::abs2rel('/path/to/foo/bar/baz', '/path/to/foo');  // returns "bar/baz"
```

## License

This library is released under the [MIT License](LICENSE).

## Contributing

[Contributions](contributing.md) are welcome!
