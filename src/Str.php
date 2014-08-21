<?php
/**
 * The file for the Str class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2014 Jack Clayton
 * @license    MIT License <http://opensource.org/licenses/MIT>
 * @package    Jstewmc/PhpHelpers <https://github.com/jstewmc/php-helpers>
 */
 
namespace Jstewmc\PhpHelpers;
 
/**
 * The string (aka, "str") utility class
 *
 * @since  0.1.0
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
	 * @since   0.1.0
	 * @param   string  $haystack  the string to search 
	 * @param   string  $needle    the substring to search for
	 * @return  bool               true if $haystack ends with $needle
	 * @throws  \BadMethodCallException    if $haystack or $needle is omitted
	 * @throws  \InvalidArgumentException  if $haystack is not a string
	 * @throws  \InvalidArgumentException  if $needle is not a string
	 * @see     \Jstewmc\PhpHelpers\Str::iEndsWith() (case-insensitive version)
	 * @see     <http://stackoverflow.com/questions/834303> (MrHus' answer)
	 */
	public static function endsWith($haystack, $needle) 
	{
		$endsWith = false;
		
		// if $haystack and $needle are passed
		if ($haystack !== null && $needle !== null) {
			// if $haystack is a string
			if (is_string($haystack)) {
				// if $needle is a string
				if (is_string($needle)) {
					// if $haystack is not an empty string
					if (strlen($haystack) > 0) {
						// if $needle is not an empty string
						if (strlen($needle) > 0) {
							$endsWith = substr($haystack, -strlen($needle)) === $needle;
						} else {
							$endsWith = false;
						}
					} else {
						$endsWith = false;
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__." expects the second parameter, the needle, to be a string"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__." expects the first parameter, the haystack, to be a string"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__." expects two string parameters"
			);
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
	 * @since   0.1.0
	 * @param   string  $haystack  str  the string to search 
	 * @param   string  $needle    str  the substring to search for
	 * @return  bool
	 * @throws  \BadMethodCallException    if $haystack or $needle is omitted
	 * @throws  \InvalidArgumentException  if $haystack is not a string
	 * @throws  \InvalidArgumentException  if $needle is not a string
	 * @see     \Jstewmc\PhpHelpers\Str::iEndsWith() (case-sensitive version)
	 */
	public static function iEndsWith($haystack, $needle) 
	{
		$endsWith = null;
		
		// if $haystack and $needle are given
		if ($haystack !== null && $needle !== null) {
			// if $haystack is a string
			if (is_string($haystack)) {
				// if $needle is a string
				if (is_string($needle)) {
					$endsWith = self::endsWith(strtolower($haystack), strtolower($needle));		
				} else {
					throw new \InvalidArgumentException(
						__METHOD__."() expects parameter two, needle, to be a string"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__."() expects parameter one, haystack, to be a string"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__."() expects two string parameters, haystack and needle"
			);
		}
		
		return $endsWith;
	}
	
	/**
	 * Alias for the isBool() method
	 */
	 public static function is_bool($string)
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
	 * @param   string  $string  the string to test
	 * @return  bool
	 */
	public static function isBool($string)
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
	 * @since   0.1.0
	 * @param   $haystack  str   the case-insensitive string to search
	 * @param   $needle    str   the case-insensitive substring to search for
	 * @return             bool  true if $haystack ends with $needle
	 * @throws  \BadMethodCallException    if $haystack or $needle is omitted
	 * @throws  \InvalidArgumentException  if $haystack is not a string
	 * @throws  \InvalidArgumentException  if $needle is not a string
	 * @see     <http://stackoverflow.com/questions/834303> (MrHus' answer)
	 * @see     \Jstewmc\PhpHelpers\Str::startsWith() (case-sensitive version)
	 */ 
	public static function iStartsWith($haystack, $needle) 
	{
		$startsWith = null;
		
		// if $haystack and $needle are given
		if ($haystack !== null && $needle !== null) {
			// if $haystack is a string
			if (is_string($haystack)) {
				// if $needle is a string
				if (is_string($needle)) {
					$startsWith = self::startsWith(strtolower($haystack), strtolower($needle));	
				} else {
					throw new \InvalidArgumentException(
						__METHOD__."() expects parameter two, needle, to be a string"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__."() expects parameter one, haystack, to be a string"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__."() expects two string parameters, haystack and needle"
			);
		}
		
		return $startsWith;
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
	 * @since   0.1.0
	 * @param   int    $length  the length of the password (optional; if omitted, 
	 *     defaults to 8)
	 * @param   int[]  $rules   an array of character counts indexed by charset name
	 *     (possible charset names are 'lower', 'upper', 'number', 'alpha', and 'symbol') 
	 *     (optional; if omitted, defaults to ['lower' => 1, 'upper' => 1, 'number' => 1, 
	 *     'symbol' => 1])
	 * @return  string          the password
	 * @throws  \BadMethodCallException    if $rules or $length is omitted
	 * @throws  \InvalidArgumentException  if $rules is not an array
	 * @throws  \InvalidArgumentException  if $length is not an integer
	 * @throws  \InvalidArgumentException  if a key in $rules is not a valid charset name 
	 * @throws  \InvalidArgumentException  if a value in $rules is not an integer
	 * @throws  \InvalidArgumentException  if the number of required characters (as defined
	 *     in the $rules array) exceeds the $length
	 * @see     \Jstewmc\PhpHelpers\Str::rand() 
	 */ 
	public static function password($length = 8, $rules = array('lower' => 1, 'upper' => 1, 'number' => 1, 'symbol' => 1)) 
	{
		$password = '';
		
		// if $rules and $length are given
		if ($rules !== null && $length !== null) {
			// if $rules is an array
			if (is_array($rules)) {
				// if $length is an integer
				if (is_numeric($length) && is_int(+$length)) {
					// if the number of required characters is LTE the desired length
					if (array_sum($rules) <= $length) {
						// loop through the password's rules
						foreach ($rules as $charset => $number) {
							$password .= self::rand($number, $charset);
						}
						// if any characters are missing, add them
						if ($length - strlen($password) > 0) {
							$password .= self::rand($length - strlen($password));
						}
						// shuffle the password
						$password = str_shuffle($password);
					} else {
						throw new \InvalidArgumentException(
							__METHOD__." expects the number of required characters to be less than or ".
								"equal to the length"
						);
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__." expects the seond parameter, length, to be an integer"
					);
				}
			} else {
				 throw new \InvalidArgumentException(
				 	__METHOD__." expects the first parameter, rules, to be an array"
				 );
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__." expects two parameters, an array of charset rules and a length"
			);
		}
		
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
	 * @since   0.1.0
	 * @param   int    $length    the length of the string to return
	 * @param   mixed  $charsets  a string charset name or an array of charset names
	 *     (possible values are are 'lower', 'upper', 'alpha' (a combination of 'upper'
	 *     and 'lower'), 'number', and 'symbol') (optional; if omitted, defaults to 
	 *     ['alpha', 'number', 'symbol'])
	 * @return  string            a random string
	 * @throws  \BadMethodCallException    if $length or $charset is null
	 * @throws  \InvalidArgumentException  if $length is not an integer
	 * @throws  \InvalidArgumentException  if $charsets is not a string or array
	 * @throws  \InvalidArgumentException  if a given $charset is not a valid charset
	 */
	public static function rand($length, $charsets = array('alpha', 'number', 'symbol'))
	{
		$rand = '';
		
		// if $length and $charsets are given
		if ($length !== null && $charsets !== null) {
			// if $length is an integer
			if (is_numeric($length) && is_int(+$length)) {
				// if $charsets is a string or array
				if (is_string($charsets) || is_array($charsets)) {
					// if $charsets is a string, array-ify it
					if (is_string($charsets)) {
						$charsets = (array) $charsets;
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
								__METHOD__." expects parameter two to be a string charset name or an array ".
									"of charset names such as 'lower', 'upper', 'alpha', 'number', or 'symbol'"
							);
						}
					}
			
					// shuffle the chars
					shuffle($chars);
			
					// pick $length random chars
					for ($i=0; $i<$length; ++$i) {
						$rand .= $chars[array_rand($chars)];
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__." expects the second parameter, charsets, to be a string charset ".
							"name or an array of charset names"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__." expects the first parameter, length, to be an integer"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__." expects at least one argument, length"
			);
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
	 * @since   0.1.0
	 * @param   string    $string  the string to split
	 * @return  string[]           an array
	 * @throws  \BadMethodCallException    if $string is null
	 * @throws  \InvalidArgumentException  if $string is not a string
	 * @see     <http://stackoverflow.com/questions/18990180> (FrankieTheKneeMan)
	 *          (using Regex lookahead)
	 */
	public static function splitOnFirstAlpha($string)
	{
		$pieces = array();
		
		// if $string is not null
		if ($string !== null) {
			// if $string is actually a string
			if (is_string($string)) {
				$pieces = array_map('trim', preg_split('/(?=[a-zA-Z])/i', $string, 2));		
			} else {
				throw new \InvalidArgumentException(
					__METHOD__."() expects parameter one to be a string"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__."() expects one parameter, a string"
			);
		}
		
		return $pieces; 
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
	 * @since   0.1.0
	 * @param   string  $haystack  the string to search
	 * @param   string  $needle    the substring to search for
	 * @return  bool               true if $haystack starts with $needle
	 * @throws  \BadMethodCallException    if $haystack or $needle is omitted
	 * @throws  \InvalidArgumentException  if $haystack is not a string
	 * @throws  \InvalidArgumentException  if $needle is not a string
	 * @see     \Jstewmc\PhpHelpers\Str::startsWith() (case-insensitive version)
	 * @see     <http://stackoverflow.com/questions/834303> (MrHus' answer)
	 */ 
	public static function startsWith($haystack, $needle) 
	{
		$startsWith = false;
		
		// if $haystack and $needle are given
		if ($haystack !== null && $needle !== null) {
			// if $haystack is a string
			if (is_string($haystack)) {
				// if $needle is a string
				if (is_string($needle)) {
					// if $haystack is not empty
					if (strlen($haystack) > 0) {
						// if $needle is not empty
						if (strlen($needle) > 0) {
							$startsWith = ! strncmp($haystack, $needle, strlen($needle));
						} else {
							$startsWith = false;
						}
					} else {
						$startsWith = false;
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__." expects the second parameter, the needle, to be a string"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__." expects the first parameter, the haystack, to be a string"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__." expects two string parameters, haystack and needle"
			);
		}
		
		return $startsWith;
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
	 * @since   0.1.0
	 * @param   string     $string  the string to convert
	 * @return  int|float           the number of bytes
	 * @throws  \BadMethodCallException    if $string is null
	 * @throws  \InvalidArgumentException  if $string is not a string
	 * @throws  \InvalidArgumentException  if $string does not end in 'k', 'm', or 'g'
	 * @see     <http://www.php.net/manual/en/function.ini-get.php>
	 */
	public static function strtobytes($string)
	{
		$val = false;
		
		// if $string is given
		if ($string !== null) {
			// if $string is actually a string
			if (is_string($string)) {
				// get the string's last character
				$val  = trim($string);
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
						break;
					
					default:
						throw new \InvalidArgumentException(
							__METHOD__." expects the first parameter to end in 'k', 'm', or 'g'"		
						);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__." expects the first parameter to be a string"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__." expects one parameter"
			);
		}

		return $val;
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
	 * @since   0.1.0
	 * @param   string  $string  the string to camel-case
	 * @return  string           the camel-cased string
	 * @throws  \BadMethodCallException    if $string is empty
	 * @throws  \InvalidArgumentException  if $string is not a string
	 */
	public static function strtocamelcase($string)
	{		
		// if $string is given
		if ($string !== null) {
			// if $string is actually a string
			if (is_string($string)) {
				// if $string is not empty
				if (strlen($string)) {
					// trim the string
					$string = trim($string);
			
					// replace underscores ("_") and hyphens ("-") with spaces (" ")
					$string = str_replace(array('-', '_'), ' ', $string);
			
					// capitalize each word
					$string = ucwords($string);
			
					// remove spaces
					$string = str_replace(' ', '', $string);
			
					// lower-case the first word
					$string = lcfirst($string);
			
					// remove any non-alphanumeric characters
					$string = preg_replace("#[^a-zA-Z0-9]+#", '', $string);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__." expects the first parameter, the string, to be a string"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__." expects one parameter, a string to camel-case"
			);
		}

		return $string;
	}

	/**
	 * Truncates $string to a preferred length
	 *
	 *     Str::truncate('Lorem ipsum inum', 8);               // returns 'Lorem ipsum...'
	 *     Str::truncate('Lorem ipsum inum', 8, '');           // returns 'Lorem ip...'
	 *     Str::truncate('Lorem ipsum inum', 8, ' ', ' >>>');  // returns 'Lorem ipsum >>>'
	 *
	 * @since   0.1.0
	 * @param   string  $str    the string to truncate
	 * @param   int     $limit  the string's max length
	 * @param   string  $break  the break character (to truncate at exact length set to 
	 *     empty string or null) (if the break character does not exist in the string, 
	 *     the string will be truncated at limit) (optional; if omitted, defaults to ' ') 
	 * @param   string  $pad    the padding to add to end of string (optional; if 
	 *     omitted, defaults to '...')
	 * @return  string          the truncated string
	 * @throws  \BadMethodCallException    if $string or $limit is omitted
	 * @throws  \InvalidArgumentException  if $string is not a string
	 * @throws  \InvalidArgumentException  if $limit is not an integer (or integer string)
	 * @throws  \InvalidArgumentException  if $break is not a string or null
	 * @throws  \InvalidArgumentException  if $pad is not a string or null
	 * @see     <http://blog.justin.kelly.org.au/php-truncate/>
	 */
	public static function truncate($string, $limit, $break = ' ', $pad = '...')
	{
		$truncated = null;
		
		// if $string and $limit are given
		if ($string !== null && $limit !== null) {
			// if $string is actually a string
			if (is_string($string)) {
				// if $limit is a number
				if (is_numeric($limit) && is_int(+$limit)) {
					// if $break is a string or it's null
					if (is_string($break) || is_null($break)) {
						// if $pad is a string or it's null
						if (is_string($pad) || is_null($pad)) {
							// if $string is longer than $limit
							if (strlen($string) > $limit) {
								// truncate the string at the limit
								$truncated = substr($string, 0, $limit);
								// if a break character is defined and it exists in the truncated string
								if ($break !== null && $break !== '' && strpos($truncated, $break)) {
									$truncated = substr($truncated, 0, strrpos($truncated, $break));
								}
								// if a pad exists, use it
								if ($pad !== null && $pad !== '') {
									$truncated .= $pad;
								}
							} else {
								$truncated = $string;
							}
						} else {
							throw new \InvalidArgumentException(
								__METHOD__."() expects the fourth parameter, pad, to be a string or null"
							);
						}
					} else {
						throw new \InvalidArgumentException(
							__METHOD__."() expects the third parameter, break, to be a string or null"
						);
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__."() expects the second parameter, limit, to be an integer"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__."() expects the first parameter, the string, to be a string"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__."() expects at least two parameters, a string and an integer length limit"
			);
		}

		return $truncated;
	}
}
 