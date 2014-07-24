# PHP Helpers
Static classes to help with PHP strings, arrays, numbers, files, and bools.

Static helper classes are nothing new in PHP. In fact, most of these functions have probably been written dozens of times in better libraries than mine. However, I wrote (or copied these functions from the web) when I worked on a project that required as few dependencies as possible, and I figured they were a great candidate for my first GitHub repository.

## Examples
```php
echo Num::val('1/2');       // prints (float) 0.5
echo Num::roundTo(15, 20);  // prints (float) 20
echo Num::isInt('1,000');   // prints (bool) true

echo Str::rand(8, ['alpha', 'number']);      // prints string like '9Ohb5Fv3'
echo Str::truncate('Lorem ipsum inum', 12);  // prints 'Lorem ipsum...'
echo Str::endsWith('foobar', 'bar');         // prints (bool) true

echo Bool::booltostr(true, 'yes/no');  // prints (string) 'yes'
echo Bool::val('on');                  // prints (bool) true

$a = Arr::usort_field('foo', [['foo' => 3], ['foo' => 1], ['foo' => 2]]);   
$b = Arr::in_array_wildcard('qu*', ['foo', 'bar', 'baz', 'qux']);   
$c = Arr::array_filter_key(['foo' => 'bar', 'baz' => 'qux'], 'foo'); 
 
var_dump($a);  // prints [['foo' => 3], ['foo' => 2], ['foo' => 1]]
var_dump($b);  // prints (bool) true
var_dump($c);  // prints ['foo' => 'bar']
```

## Todo
1. Create README.md
2. Create package for composer
3. Create unit tests