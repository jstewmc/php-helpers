<?php
/**
 * A string (aka, "str") utility class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2014 Jack Clayton
 * @license    MIT License <http://opensource.org/licenses/MIT>
 * @package    Jstewmc/PhpHelpers <https://github.com/jstewmc/php-helpers>
 * @since      0.0.0
 *
 */
 
namespace Jstewmc/PhpHelpers;
 
class Str
{
	/**
	 * Returns true if $haystack ends with $needle (case-sensitive)
	 *
	 *     Str::endsWith('foobar', 'bar');  // returns true
	 *     Str::endsWith('foobar', 'baz');  // returns false
	 *     Str::endsWith('foobar', 'BAR');  // returns false
	 * 
	 * @static
	 * @access  public
	 * @see     self::iEndsWith (case-insensitive version)
	 * @see     http://stackoverflow.com/questions/834303 (MrHus' answer)
	 * @param   $haystack  str  the string to search 
	 * @param   $needle    str  the substring to search for
	 * @return             bool
	 *
	 */
	public static function endsWith($haystack, $needle) 
	{
		$len = strlen($needle);
		if ($len != 0) {
			$endsWith = substr($haystack, -$len) === $needle;
		} else {
			$endsWith = true;
		}

		return $endsWith;
	}

	/**
	 * Returns true if $haystack ends with $needle (case-insensitive)
	 *
	 *     Str::endsWith('foobar', 'bar');  // returns true
	 *     Str::endsWith('foobar', 'baz');  // returns false
	 *     Str::endsWith('foobar', 'BAR');  // returns true
	 * 
	 * @static
	 * @access  public
	 * @see     self::iEndsWith() (case-sensitive version)
	 * @see     http://stackoverflow.com/questions/834303 (MrHus' answer)
	 * @param   $haystack  str  the string to search 
	 * @param   $needle    str  the substring to search for
	 * @return             bool
	 *
	 */
	public static function iEndsWith($haystack, $needle) 
	{
		return self::endsWith(strtolower($haystack), strtolower($needle));
	}

	/**
	 * Returns true if the string is a bool value
	 * 
	 * I extend PHP's native is_bool() method to test strings like 'true' or 'no'.
	 *
	 *     Str::isBool(true);    // returns false
	 *     Str::isBool('true');  // returns true
	 *     Str::isBool('yes');   // returns true
	 * 
	 * @static
	 * @access  public
	 * @param   $str  str   the string to test
	 * @return        bool
	 *
	 */
	public static function isBool($str)
	{
		return in_array(strtolower($str), array('true', 'false', 'yes', 'no', 'on', 'off'));
	}

	/**
	 * Alias for the isBool() method
	 *
	 * @static
	 * @access  public
	 * @param   $str  str   the string to test
	 * @return        bool
	 *
	 */
	 public static function is_bool($str)
	 {
	 	return self::isBool($str);
	 }

	/**
	 * Returns true if the $haystack starts with the $needle (case-insensitive)
	 *
	 *     Str::iStartsWith('foobar', 'bar');  // returns false
	 *     Str::iStartsWith('foobar', 'foo');  // returns true
	 *     Str::iStartsWith('foobar', 'FOO');  // returns true
	 *
	 * @static   
	 * @access  public
	 * @see     self::startsWith() (case-sensitive version)
	 * @see     http://stackoverflow.com/questions/834303 (MrHus' answer)
	 * @param   $haystack  str   the case-insensitive string to search
	 * @param   $needle    str   the case-insensitive substring to search for
	 * @return             bool
	 *
	 */ 
	public static function iStartsWith($haystack, $needle) 
	{
		return self::startsWith(strtolower($haystack), strtolower($needle));
	}
	
	/**
	 * Returns a random string that follows $rules
	 *
	 * Oftetimes, standards require passwords with one upper-case letter, one lower-case 
	 * letter, one number, and one symbol. I can do that.
	 *
	 *     $rules = ['upper' => 12];
	 *     $a = Str::password($rules);
	 *
	 *     $rules = ['lower' => 6, 'upper' => 6];
	 *     $b = Str::password($rules);
	 *
	 *     $rules = ['lower' => 4, 'upper' => 4, 'number' => 4];
	 *     $c = Str::password($rules);
	 *
	 *     echo $a;  // example 'KNVHYUIDGVDS'
	 *     echo $b;  // example 'jNhGFkLekOfV'
	 *     echo $c;  // example 'la9Uh7BH4Bc3'
	 *
	 * @static
	 * @access  public
	 * @throws  InvalidArgumentException  if key in rules is not valid charset name
	 * @param   $length  int  the length of the password (optional; if omitted,
	 *                        defaults to 8)
	 * @param   $rules   arr  an array of character counts indexed by charset name
	 *                        (possible charset names are 'lower', 'upper', 'number',
	 *                        and 'symbol') (optional; if omitted, defaults to 
	 *                        ['lower' => 1, 'upper' => 1, 'number' => 1, 'symbol' => 1])
	 * @return           str  the password
	 *
	 */ 
	public static function password($rules = ['lower' => 1, 'upper' => 1, 'number' => 1, 'symbol' => 1], $length = 8) 
	{
		$password = '';
		
		// loop through the password's rules
		foreach ($rules as $charset => $num) {
			$password .= self::rand($num, $charset);
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
	 *     echo Str::rand(8, 'alpha');              // example 'hbdrckso'
	 *     echo Str::rand(8, ['lower', 'number']);  // example 'k987hb54'
	 *     echo Str::rand(8, ['upper', 'symbol']);  // example 'HG!V*X]@'
	 *
	 * @static
	 * @access  public
	 * @throws  InvalidArgumentException  if $charset is not a valid charset
	 * @param   $length    str    the length of the string to return
	 * @param   $charsets  mixed  a string charset name or an array of charset
	 *                            names (possible charset names are 'lower', 
	 *                            'upper', 'alpha' (a combination of 'upper'
	 *                            and 'lower'), 'number', and 'symbol') 
	 *                            (optional; if omitted, defaults to ['alpha',
	 *                            'number', 'symbol'])
	 * @return             str
	 *
	 */
	public static function rand($length, $charsets = array('alpha', 'number', 'symbol'))
	{
		$rand = '';

		// if $charsets is a string, array-ify it
		if (is_string($charsets)) {
			$charsets = array($charsets);
		}

		// define the possible charsets
		$lower   = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 
			'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
		$upper   = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
			'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		$number = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10');
		$symbol  = array('!', '@', '#', '*', '(', ')', '-', '_', '+', '=', '[', ']');

		// create an array of possible chars
		$chars = array();
		foreach ($charsets as $charset) {
			if (isset($$charset)) {
				$chars = array_merge($chars, $$charset);
			} elseif ($charset === 'alpha') {
				$chars = array_merge($chars, $lower, $upper);
			} else {
				throw new \InvalidArgumentException(
					"rand() expects parameter two to be a string charset name ".
						"or an array of charset names such as 'lower', 'upper', ".
						"'alpha', 'number', or 'symbol'"
				);
			}
		}

		// shuffle the chars
		shuffle($chars);

		// pick $length random chars
		for ($i=0; $i<$length; ++$i) {
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
	 *     Str::splitOnFirstAlpha("123");        // returns ["123"]
	 *     Str::splitOnFirstAlpha("abc");        // returns ["", "abc"]
	 *     Str::splitOnFirstAlpha("123 abc");    // returns ["123", "abc"]
	 *     Str::splitOnFirstAlpha("1 2 3 abc");  // returns ["1 2 3 4", "abc"]
	 * 
	 * @static
	 * @access  public
	 * @see     http://stackoverflow.com/questions/18990180 (FrankieTheKneeMan)
	 *          (using Regex lookahead)
	 * @param   $str  str  the string to split
	 * @return        arr 
	 *
	 */
	public static function splitOnFirstAlpha($str)
	{
		return array_map('trim', preg_split('/(?=[a-z])/i', $str, 2));
	}

	/**
	 * Returns true if the $haystack starts with the $needle (case-sensitive)
	 *
	 *     Str::iStartsWith('foobar', 'bar');  // returns false
	 *     Str::iStartsWith('foobar', 'foo');  // returns true
	 *     Str::iStartsWith('foobar', 'FOO');  // returns false
	 *
	 * @static   
	 * @access  public
	 * @see     self::startsWith() (case-insensitive version)
	 * @see     http://stackoverflow.com/questions/834303 (MrHus' answer)
	 * @param   $haystack  str   the string to search
	 * @param   $needle    str   the substring to search for
	 * @return             bool
	 *
	 */ 
	public static function startsWith($haystack, $needle) 
	{
		return ! strncmp($haystack, $needle, strlen($needle));
	}

	/**
	 * Converts a php.ini-like byte notation shorthand to a number of bytes
	 *
	 * In the php.ini configuration file, byte values are sote in shorthand
	 * notation (e.g., "8M"). PHP's native ini_get() function will return the
	 * exact string stored in php.ini and not its integer equivalent. I will
	 * return the integer equivalent.
	 *
	 * @static
	 * @access  public 
	 * @see     http://www.php.net/manual/en/function.ini-get.php
	 * @param   $str  str  the string to convert
	 * @return        num  the number of bytes
	 *
	 */
	public static function strtobytes($str)
	{
		$val  = trim($str);
		$last = strtolower($val[strlen($val) - 1]);

		switch ($last) {

			case 'g':
				$val *= 1024;
				// no break

			case 'm':
				$val *= 1024;
				// no break

			case 'k':
				$val *= 1024;
				// no break
		}

		return $val;
	}

	/**
	 * Returns a string in camel case
	 *
	 *     Str::strtocamelcase('Hello world');   // returns "helloWorld"
	 *     Str::strtocamelcase('H3LLO WORLD!');  // returns "helloWorld"
	 *     Str::strtocamelcase('hello_world');   // returns "helloWorld"
	 * 
	 * @static
	 * @access  public
	 * @param   $str  str  the string to camel-case
	 * @return        str  the camel-cased string
	 *
	 */
	public static function strtocamelcase($str)
	{
		// trim the string
		$str = trim($str);

		// replace underscores ("_") and hyphens ("-") with spaces (" ")
		$str = str_replace(array('-', '_'), ' ', $str);

		// capitalize each word
		$str = ucwords($str);

		// remove spaces
		$str = str_replace(' ', '', $str);

		// lower-case the first word
		$str = lcfirst($str);

		// remove any non-alphanumeric characters
		$str = preg_replace("#[^a-zA-Z0-9]+#", '', $str);

		return $str;
	}

	/**
	 * Truncates a string to a preferred length
	 *
	 *     Str::truncate('Lorem ipsum', 8);             // returns 'Lorem...'
	 *     Str::truncate('Lorem ipsum', 8, '');         // returns 'Lorem ip...'
	 *     Str::truncate('Lorem ipsum', 8, '', ' ->');  // returns 'Lorem ip ->'
	 *
	 * @static
	 * @see     http://blog.justin.kelly.org.au/php-truncate/
	 * @access  public
	 * @author  Chirp Internet <www.chirp.com.au>
	 * @param   $str    str  the string to truncate
	 * @param   $limit  int  the max length
	 * @param   $break  str  the break character (optional; if omitted, defaults 
	 *                       to ' ')
	 * @param   $pad    str  the padding to add to end of string (optional; if 
	 *                       omitted, defaults to '...')
	 * @return          str
	 */
	public static function truncate($str, $limit, $break = ' ', $pad = '...')
	{
		//  if the string is longer than $limit
		if(strlen($str) > $limit) {
			// is $break present between $limit and the end of the string?
			if(false !== ($breakpoint = strpos($str, $break, $limit))) {
				if($breakpoint < strlen($str) - 1) {
					$str = substr($str, 0, $breakpoint) . $pad;
				}
			}
		}

		return $str;
	}
}
 