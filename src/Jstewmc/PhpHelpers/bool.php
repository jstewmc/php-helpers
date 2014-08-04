<?php
/**
 * A boolean (aka, "bool") utility class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2014 Jack Clayton
 * @license    MIT License <http://opensource.org/licenses/MIT>
 * @package    Jstewmc/PhpHelpers <https://github.com/jstewmc/php-helpers>
 * @since      0.0.0
 *
 */

namespace Jstewmc/PhpHelpers;

class Bool 
{
	/**
	 * Returns a bool value as a string
	 *
	 * @static
	 * @access  public
	 * @param   $bool    bool  the boolean value to convert
	 * @param   $format  str   the string format to convert to (possible values
	 *                         't[/-]f', true[/-]false', 'y[/-]n', 'yes[/-]no', 'o[/-o]', 
	 *                         and on[/-]off')
	 *                         (case-insensitive) (optional; if omitted, 
	 *                         defaults to 'tf')
	 * @return           str   the string value
	 *
	 */
	public static function booltostr($bool, $format = 'true-false') {
		// if $bool is bool
		if (is_bool($bool)) {
			switch (strtolower($format)) {
				
				case 'oo':
				case 'o/o':
				case 'o-o':
				case 'onoff':
				case 'on/off':
				case 'on-off':
					$str = $bool ? 'on' : 'off';
					break;

				case 'tf':
				case 't/f':
				case 't-f':
				case 'truefalse':
				case 'true/false':
				case 'true-false':
					$str = $bool ? 'true' : 'false';
					break;

				case 'yn':
				case 'y/n':
				case 'y-n':
				case 'yesno':
				case 'yes/no':
				case 'yes-no':
					$str = $bool ? 'yes' : 'no';
					break;

				default:
					throw new \InvalidArgumentException(
						"booltostr() expects parameter two to be one of the ".
							"following: 't[/-]f', 'true[/-]false', 'y[/-]s', ".
							"'yes[/-]no', 'o[/-]o', or 'on[/-]off'; '$format' given"
					);
			}
		} else {
			throw new \InvalidArgumentException(
				"booltostr() expects parameter one to be a bool value; ".
					gettype($bool)." given"
			);
		}

		return $str;
	}

	/**
	 * Returns the boolean value of $var
	 *
	 * PHP's native boolval() function is not available before PHP 5.5, and it does not
	 * support the strings 'yes', 'no', 'on', or 'off'.
	 *
	 * I follow the following rules:
	 * 
	 *     Strings
	 *         The strings "yes", "true", "on", or "1" are considered true, and
	 *         the strings "no", "false", "off", or "0" are considered false 
	 *         (case-insensitive). Any other non-empty string is true. 
	 *
	 *     Numbers
	 *         The numbers 0 or 0.0 are considered false. Any other number is 
	 *         considered true.
	 *
	 *     Array
	 *         An empty array is considered false. Any other array (even an
	 *         associative array with no values) is considered true.
	 *
	 *     Object
	 *         Any object is considered true.
	 *
	 * For example...
	 *
	 *     Bool::val("");              // returns (bool) false
	 *     Bool::val(true);            // returns (bool) true
	 *     Bool::val(0);               // returns (bool) false
	 *     Bool::val(0.0);             // returns (bool) false
	 *     Bool::val('0');             // returns (bool) false
	 *     Bool::val('abc');           // returns (bool) true
	 *     Bool::val('true');          // returns (bool) true
	 *     Bool::val('on');            // returns (bool) true
	 *     Bool::val('yes');           // returns (bool) true
	 *     Bool::val('off');           // returns (bool) false
	 *     Bool::val([]);              // returns (bool) false
	 *     Bool::val([1, 2]);          // returns (bool) true
	 *     Bool::val(new StdClass());  // returns (bool) true
	 * 
	 * @static
	 * @see     http://www.php.net/manual/en/function.boolval.php
	 * @access  public
	 * @param   $var  mixed  the variable to test
	 * @return        bool   the variable's bool value
	 *
	 */
	public static function val($var)
	{
		$val = null;

		// if $var is not empty
		// any value considered empty by empty() is considered false
		// for example, "0", array(), "", etc
		// 
		if ( ! empty($var)) {
			// if $var is not already a bool type
			if ( ! is_bool($var)) {
				// if $var is a string
				if (is_string($var)) {
					// switch on the string
					// the strings '1', 'on', 'yes', and 'true' are considered true
					// the strings '0', 'no', 'off', and 'false' are considered false
					// any other non-empty string is true
					//
					switch (strtolower($var)) {

						case '1':
						case 'on':
						case 'yes':
						case 'true':
							$val = true;
							break;

						case '0':
						case 'no':
						case 'off':
						case 'false':
							$val = false;
							break;

						default:
							$val = ! empty($var);
					}
				} elseif (is_numeric($var)) {
					// any non-zero integer or float is considered true
					$val = ($str !== 0 && $str !== 0.0);
				} elseif (is_object($var)) {
					// any object is considered true
					$val = true;
				} elseif (is_array($var)) {
					// any non-empty array is considered true
					$val = ! empty($var);
				}
			}
		} else {
			$val = false;
		}

		return $val;
	}
}
