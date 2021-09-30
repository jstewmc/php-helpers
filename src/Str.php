<?php

namespace Jstewmc\PhpHelpers;

/**
 * The string (aka, "str") class
 */
class Str
{
	/**
	 * Returns true if $haystack ends with $needle (case-sensitive)
	 *
	 * For example:
	 *
	 *     Str::endsWith('foobar', 'bar');  // returns true
	 *     Str::endsWith('foobar', 'baz');  // returns false
	 *     Str::endsWith('foobar', 'BAR');  // returns false
	 *     Str::endsWith('foobar', '');     // returns false
	 *     Str::endsWith('', 'foobar');     // returns false
	 *
	 * @param  string  $haystack  the string to search
	 * @param  string  $needle    the substring to search for
	 *
	 * @return  bool  true if $haystack ends with $needle
	 *
	 * @see  \Jstewmc\PhpHelpers\Str::iEndsWith()  case-insensitive version
	 * @see  http://stackoverflow.com/a/834355  MrHus' answer to "startsWith()
	 *    and endsWith() functions in PHP" on StackOverflow
	 */
	public static function endsWith(string $haystack, string $needle): bool
	{
		if (strlen($haystack) > 0 && strlen($needle) > 0) {
			return substr($haystack, -strlen($needle)) === $needle;
		}

		return false;
	}

	/**
	 * Returns true if $haystack ends with $needle (case-insensitive)
	 *
	 *     Str::endsWith('foobar', 'bar');  // returns true
	 *     Str::endsWith('foobar', 'baz');  // returns false
	 *     Str::endsWith('foobar', 'BAR');  // returns true
	 *
	 * @param  string  $haystack  str  the string to search
	 * @param  string  $needle    str  the substring to search for

	 * @return  bool
	 *
	 * @see  \Jstewmc\PhpHelpers\Str::iEndsWith()  case-sensitive version
	 */
	public static function iEndsWith(string $haystack, string $needle): bool
	{
		return self::endsWith(strtolower($haystack), strtolower($needle));
	}

	/**
	 * Alias for the isBool() method
	 *
	 * @see  \Jstewmc\PhpHelpers\Str::isBool()
	 */
	 public static function is_bool($string): bool
	 {
	 	return self::isBool($string);
	 }

	/**
	 * Returns true if $string is a bool string
	 *
	 * I'll return true if $string is a bool string like 'true', 'false', 'yes', 'no',
	 * 'on' or 'off'. Keep in mind, I only handle strings. I will return false if you
	 * test an actual bool value (because it's not a string).
	 *
	 *     is_bool(true);        // returns true
	 *     Str::is_bool(true);   // returns false
	 *
	 *     is_bool('true');      // returns false
	 *     Str::isBool('true');  // returns true
	 *
	 *     is_bool('yes');       // returns false
	 *     Str::isBool('yes');   // returns true
	 *
	 * @param  string  $string  the string to test
	 *
	 * @return  bool
	 */
	public static function isBool($string): bool
	{
		return is_string($string)
			&& in_array(strtolower($string), array('true', 'false', 'yes', 'no', 'on', 'off'));
	}

	/**
	 * Returns true if $haystack starts with $needle (case-insensitive)
	 *
	 * For example:
	 *
	 *     Str::iStartsWith('foobar', 'bar');  // returns false
	 *     Str::iStartsWith('foobar', 'foo');  // returns true
	 *     Str::iStartsWith('foobar', 'FOO');  // returns true
	 *     Str::iStartsWith('', 'foobar');     // returns false
	 *     Str::iStartsWith('foobar', '');     // returns false
	 *
	 * @param  string  $haystack  the case-insensitive string to search
	 * @param  string  $needle    the case-insensitive substring to search for
	 *
	 * @return  bool  true if $haystack ends with $needle
	 *
	 * @see     \Jstewmc\PhpHelpers\Str::startsWith()  case-sensitive version
	 */
	public static function iStartsWith(string $haystack, string $needle): bool
	{
		return self::startsWith(strtolower($haystack), strtolower($needle));
	}

	/**
	 * Returns a random string of $length that follows the charset $rules
	 *
	 * Oftetimes, standards (like PCI) require passwords with one upper-case letter, one
	 * lower-case letter, one number, and one symbol. I can do that.
	 *
	 * For example:
	 *
	 *     $rules = ['upper' => 12];
	 *     $a = Str::password(12, $rules);
	 *
	 *     $rules = ['lower' => 6, 'upper' => 6];
	 *     $b = Str::password(12, $rules);
	 *
	 *     $rules = ['lower' => 4, 'upper' => 4, 'number' => 4];
	 *     $c = Str::password(12, $rules);
	 *
	 *     echo $a;  // example 'KNVHYUIDGVDS'
	 *     echo $b;  // example 'jNhGFkLekOfV'
	 *     echo $c;  // example 'la9Uh7BH4Bc3'
	 *
	 * @param  int    $length  the length of the password (optional; if omitted,
	 *    defaults to 8)
	 * @param  int[]  $rules   an array of character counts indexed by charset name
	 *    (possible charset names are 'lower', 'upper', 'number', 'alpha', and 'symbol')
	 *    (optional; if omitted, defaults to ['lower' => 1, 'upper' => 1, 'number' => 1,
	 *    'symbol' => 1])
	 *
	 * @return  string  the password
	 *
	 * @throws  \InvalidArgumentException  if a key in $rules is not a valid charset name
	 * @throws  \InvalidArgumentException  if a value in $rules is not an integer
	 * @throws  \InvalidArgumentException  if the number of required characters (as defined
	 *    in the $rules array) exceeds the $length
	 */
	public static function password(
		int $length = 8,
		array $rules = ['lower' => 1, 'upper' => 1, 'number' => 1, 'symbol' => 1]
	): string {
		// if the number of required characters is LTE the desired length
		if (array_sum($rules) > $length) {
			throw new \InvalidArgumentException(
				"the number of required characters should be less than or equal to the length"
			);
		}

		// loop through the password's rules
		$password = '';
		foreach ($rules as $charset => $number) {
			$password .= self::rand($number, $charset);
		}

		// if any characters are missing, add them
		if ($length - strlen($password) > 0) {
			$password .= self::rand($length - strlen($password));
		}

		// shuffle the password
		$password = str_shuffle($password);

		return $password;
	}

	/**
	 * Returns a random string
	 *
	 * For example:
	 *
	 *     echo Str::rand(8, 'alpha');              // example 'hbdrckso'
	 *     echo Str::rand(8, ['lower', 'number']);  // example 'k987hb54'
	 *     echo Str::rand(8, ['upper', 'symbol']);  // example 'HG!V*X]@'
	 *
	 * @param  int    $length    the length of the string to return
	 * @param  mixed  $charsets  a string charset name or an array of charset names
	 *    (possible values are are 'lower', 'upper', 'alpha' (a combination of 'upper'
	 *    and 'lower'), 'number', and 'symbol') (optional; if omitted, defaults to
	 *    ['alpha', 'number', 'symbol'])
	 *
	 * @return  string  a random string
	 *
	 * @throws  \InvalidArgumentException  if $charsets is not a string or array
	 * @throws  \InvalidArgumentException  if a given $charset is not a valid charset
	 */
	public static function rand(int $length, $charsets = ['alpha', 'number', 'symbol']): string
	{
		$rand = '';

		if (is_string($charsets)) {
			$charsets = (array)$charsets;
		}

		if (!is_array($charsets)) {
			throw new \InvalidArgumentException(
				"the second parameter, charsets, should be a string charset ".
					"name or an array of charset names"
			);
		}

		// define the possible charsets
		$lower  = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l',
			'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
		$upper  = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
			'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		$number = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
		$symbol = array('!', '@', '#', '*', '(', ')', '-', '_', '+', '=', '[', ']');

		// create an array of possible chars
		$chars = array();
		foreach ($charsets as $charset) {
			if (isset($$charset)) {
				$chars = array_merge($chars, $$charset);
			} elseif ($charset === 'alpha') {
				$chars = array_merge($chars, $lower, $upper);
			} else {
				throw new \InvalidArgumentException(
					__METHOD__." expects parameter two to be a string charset name or an array ".
						"of charset names such as 'lower', 'upper', 'alpha', 'number', or 'symbol'"
				);
			}
		}

		// shuffle the chars
		shuffle($chars);

		// pick $length random chars
		for ($i = 0; $i < $length; ++$i) {
			$rand .= $chars[array_rand($chars)];
		}

		return $rand;
	}

	/**
	 * Splits a string on the first alpha character
	 *
	 * I'll return an array with two parts. The first element is the string part before
	 * the first alpha character, and the second part is everything after and including
	 * the first alpha character.
	 *
	 * For example:
	 *
	 *     Str::splitOnFirstAlpha("123");        // returns ["123"]
	 *     Str::splitOnFirstAlpha("abc");        // returns ["", "abc"]
	 *     Str::splitOnFirstAlpha("123 abc");    // returns ["123", "abc"]
	 *     Str::splitOnFirstAlpha("1 2 3 abc");  // returns ["1 2 3 4", "abc"]
	 *
	 * @param  string  $string  the string to split
	 *
	 * @return  string[]  an array
	 *
	 * @see  http://stackoverflow.com/a/18990341  FrankieTheKneeMan's answer to "Split
	 *    string on first occurrence of a letter" on StackOverflow (version using Regex
	 *    lookahead)
	 */
	public static function splitOnFirstAlpha(string $string): array
	{
		$string = trim($string);

		if ($string === '') {
			return [];
		}

		return array_map('trim', preg_split('/(?=[a-zA-Z])/i', $string, 2));
	}

	/**
	 * Returns true if $haystack starts with $needle (case-sensitive)
	 *
	 * For example:
	 *
	 *     Str::startsWith('foobar', 'bar');  // returns false
	 *     Str::startsWith('foobar', 'foo');  // returns true
	 *     Str::startsWith('foobar', 'FOO');  // returns false
	 *     Str::startsWith('foobar', '');     // returns false
	 *     Str::startsWith('', 'foobar');     // returns false
	 *
	 * @param  string  $haystack  the string to search
	 * @param  string  $needle    the substring to search for
	 *
	 * @return  bool  true if $haystack starts with $needle
	 *
	 * @see  \Jstewmc\PhpHelpers\Str::iStartsWith()  case-insensitive version
	 * @see  http://stackoverflow.com/a/834355  MrHus' answer to "startsWith() and
	 *    endsWith() functions in PHP" on StackOverflow
	 */
	public static function startsWith(string $haystack, string $needle): bool
	{
		if (strlen($haystack) > 0 && strlen($needle) > 0) {
			return !strncmp($haystack, $needle, strlen($needle));
		}

		return false;
	}

	/**
	 * Converts a php.ini-like byte notation shorthand to a number of bytes
	 *
	 * In the php.ini configuration file, byte values are sote in shorthand
	 * notation (e.g., "8M"). PHP's native ini_get() function will return the
	 * exact string stored in php.ini and not its integer equivalent. I will
	 * return the integer equivalent.
	 *
	 * For example:
	 *
	 *     Str::strtobytes('1K');  // returns 1024
	 *     Str::strtobytes('1M');  // returns 1048576
	 *     Str::strtobytes('1G');  // returns 1073741824
	 *
	 * @param  string  $string  the string to convert
	 *
	 * @return  int|float  the number of bytes
	 *
	 * @throws  \InvalidArgumentException  if $string does not end in 'k', 'm', or 'g'
	 *
	 * @see     http://www.php.net/manual/en/function.ini-get.php  ini_get() man page
	 */
	public static function strtobytes(string $string): int
	{
		$string = trim($string);

		$last = strtolower($string[strlen($string) - 1]);

		$value = 1;
		switch ($last) {
			case 'g':
				$value *= 1024;
				// no break

			case 'm':
				$value *= 1024;
				// no break

			case 'k':
				$value *= 1024;
				break;

			default:
				throw new \InvalidArgumentException(
					"the string should end in 'k', 'm', or 'g'"
				);
		}

		return $value;
	}

	/**
	 * Returns a string in camel case
	 *
	 * For example:
	 *
	 *     Str::strtocamelcase('Hello world');   // returns "helloWorld"
	 *     Str::strtocamelcase('H3LLO WORLD!');  // returns "helloWorld"
	 *     Str::strtocamelcase('hello_world');   // returns "helloWorld"
     *
	 * @param  string  $string  the string to camel-case
	 *
	 * @return  string  the camel-cased string
	 */
	public static function strtocamelcase(string $string): string
	{
		$string = trim($string);

		// replace underscores ("_") and hyphens ("-") with spaces (" ")
		$string = str_replace(['-', '_'], ' ', $string);

		// lower-case everything
		$string = strtolower($string);

		// capitalize each word
		$string = ucwords($string);

		// remove spaces
		$string = str_replace(' ', '', $string);

		// lower-case the first word
		$string = lcfirst($string);

		// remove any non-alphanumeric characters
		$string = preg_replace("#[^a-zA-Z0-9]+#", '', $string);

		return $string;
	}

	/**
	 * Truncates $string to a preferred length
	 *
	 *     Str::truncate('Lorem ipsum inum', 8);             // returns 'Lorem ipsum...'
	 *     Str::truncate('Lorem ipsum inum', 8, '');         // returns 'Lorem ip...'
	 *     Str::truncate('Lorem ipsum inum', 8, ' ', ' >');  // returns 'Lorem ipsum >'
	 *
	 * @param  string  $str    the string to truncate
	 * @param  int     $limit  the string's max length
	 * @param  string  $break  the break character (to truncate at exact length set to
	 *    empty string or null) (if the break character does not exist in the string,
	 *    the string will be truncated at limit) (optional; if omitted, defaults to ' ')
	 * @param  string  $pad    the padding to add to end of string (optional; if
	 *    omitted, defaults to '...')
	 *
	 * @return  string  the truncated string
	 *
	 * @see     http://blog.justin.kelly.org.au/php-truncate/  The original function
	 *    from "Best PHP Truncate Function" posted 6/27/12 on "Justin Kelly - various
	 *    ramblings" (edited to find closest break *before* limit and truncate string
	 *    exactly if break does not exist)
	 */
	public static function truncate(
		string $string,
		int $limit,
		?string $break = ' ',
		?string $pad = '...'
	): string {
		// if the string doesn't need truncating, short-circuit
		if (strlen($string) <= $limit) {
			return $string;
		}

		// truncate the string at the limit
		$truncated = substr($string, 0, $limit);

		// if a break character is defined and it exists in the truncated string
		if ($break && strpos($truncated, $break)) {
			$truncated = substr($truncated, 0, strrpos($truncated, $break));
		}

		// if a pad exists, use it
		if ($pad) {
			$truncated .= $pad;
		}

		return $truncated;
	}
}
