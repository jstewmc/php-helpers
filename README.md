# PHP Helpers
Static classes to help with PHP strings, arrays, numbers, files, and bools.

Static helper classes are nothing new in PHP. In fact, most of these functions have probably been written dozens of times in better libraries than mine. However, I wrote (or copied these functions from the web) when I worked on a project that required as few dependencies as possible. I figured they were a great candidate for my first GitHub repository.

Feel free to ask questions and make suggestions.

## Examples
```php
// number (aka, "num") functions
echo Num::val('1/2');                         // prints (float) 0.5
echo Num::roundTo(15, 20);                    // prints (float) 20
echo Num::ceilTo(9, 10);                      // prints (int) 10
echo Num::floorTo(11, 10);                    // prints (int) 10
echo Num::isInt('1,000');                     // prints (bool) true
echo Num::isId(1000000, 'tinyint');           // prints (bool) false
echo Num::isZero('0');                        // prints (bool) true
echo Num::almostEqual((float) 1, (float) 1);  // prints (bool) true

// string (aka, "str") functions
echo Str::rand(8, ['alpha', 'number']);       // prints string like '9Ohb5Fv3'
echo Str::truncate('Lorem ipsum inum', 12);   // prints (string) 'Lorem ipsum...'
echo Str::endsWith('foobar', 'bar');          // prints (bool) true
echo Str::startsWith('foobar', 'foo');        // prints (bool) true
echo Str::isBool('yes');                      // prints (bool) true
echo Str::strtocamelcase('hello_WORLD');      // prints (string) 'helloWorld'
var_dump(Str::splitOnFirstAlpha('123 foo'));  // prints ['123', 'foo'];

// boolean (aka, "bool") functions
echo Bool::booltostr(true, 'yes/no');         // prints (string) 'yes'
echo Bool::val('on');                         // prints (bool) true

// array (aka, "arr") functions
echo Arr::in_array_wildcard('qu*', ['foo', 'bar', 'baz', 'qux']);  // returns true
echo Arr::is_assoc([1 => 'foo', 2 => 'bar', 'baz' => 'qux']);      // returns true
echo Arr::array_key_value_exists('foo', ['foo' => null]);          // returns false

// array key functions
$a = ['foo' => 'bar', 'baz' => 'qux'];
$b = Arr::array_filter_key($arr, function ($k) {
	return $k == 'foo';
});
$c = Arr::array_filter_key_prefix($arr, 'f');
$d = Arr::array_key_str_ireplace('f', 'g', $a);

var_dump($a);  // prints ['foo' => 'bar', 'baz' => 'qux']
var_dump($b);  // prints ['foo' => 'bar']
var_dump($c);  // prints ['foo' => 'bar']
var_dump($d);  // prints ['goo' => 'bar', 'baz' => 'qux']

// array sort functions
$a = [['foo' => 1], ['foo' => 2], ['foo' => 3]];
$b = Arr::usort_field('foo', 'desc');

var_dump($a);  // prints [['foo' => 1], ['foo' => 2], ['foo' => 3]]
var_dump($b);  // prints [['foo' => 3], ['foo' => 2], ['foo' => 1]]

// define a simple class for the next example
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
$b = Arr::usort_property($a, 'bar', 'asc');
$c = Arr::usort_method($a, 'getBar', 'asc');

var_dump($b);  // prints [{bar: 1}, {bar: 2}, {bar: 3}] (psuedo-code)
var_dump($c);  // prints [{bar: 1}, {bar: 2}, {bar: 3}] (psuedo-code)

```

## Todo
1. Test (er, I made changes since I last used these, and I probably introduced an error)
2. ~~Finish README.md~~
3. Create Composer package
4. Create unit tests