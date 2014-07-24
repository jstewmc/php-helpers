<?php
/**
 * A number (aka, "num") utility class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @license    WTFPL <http://www.wtfpl.net>
 * @package    Jsc/php-helpers
 * @since      July 2014
 *
 */

namespace Jsc;

class Num 
{
	/**
	 * Returns true if float $a is almost equal to float $b
	 *
	 * Floating point numbers should never be compared for equivalence because
	 * of the way they are stored internally. They have limited precision, and
	 * many numbers that are representable as floating point numbers in base 10
	 * (e.g., 0.1 or 0.7) do not have an exact representation as floating point
	 * numbers in base 2.
	 *
	 * To test floating point values for equality, an upper bound on the 
	 * relative error due to rounding is used. This value is known as the machine 
	 * epsilon, and is the largest acceptable difference in calculations 
	 * (exclusive).
	 * 
	 * @static
	 * @access  public
	 * @see     http://www.php.net/manual/en/language.types.float.php
	 * @param   $a        num   the first value
	 * @param   $b        num   the second value
	 * @param   $epsilon  num   the maximum difference (exclusive) (optional; if 
	 *                          omitted defaults to 0.00001)
	 * @return            bool
	 *
	 */
	public static function almostEqual($a, $b, $epsilon = 0.00001) 
	{
		return (abs($a - $b) < $epsilon);
	}

	/**
	 * Bounds a value between an upper and lower bound (inclusive), greater than or 
	 * equal to a lower bound, or less than or equal to an upper bound
	 *
	 *     Num::bound(1, 0);         // returns 1
	 *     Num::bound(0, 1);         // returns 1
	 *     Num::bound(-1, null, 2);  // returns -1
	 *     Num::bound(3, null, 2);   // returns 2
	 *     Num::bound(2, 1, 3);      // returns 2
	 *     Num::bound(4, 1, 3);      // returns 3
	 * 
	 * @static
	 * @access  public
	 * @throws  BadMethodCallException  if neither $lower or $upper are defined
	 * @param   $val    num  the value to bound
	 * @param   $lower  num  the lower bound (inclusive)
	 * @param   $upper  num  the upper bound (inclusive)
	 * @return
	 *
	 */
	public static function bound($val, $lower = null, $upper = null) 
	{
		if ( ! empty($lower) && ! empty($upper)) {
			$val = min(max($val, $lower), $upper);
		} elseif ( ! empty($lower)) {
			$val = max($val, $lower);
		} elseif ( ! empty($upper)) {
			$val = min($val, $upper);
		} else {
			throw new \BadMethodCallException(
				"bound() expects an upper bound, a lower bound, or both"
			);
		}

		return $val;
	}

	/**
	 * Returns the number ceiling-ed to the nearest multiple of $multiple
	 *
	 *     Num::ceilTo(5, 2);    // returns 6
	 *     Num::ceilTo(15, 10);  // returns 20
	 *     Num::ceilTo(25, 40);  // returns 40
	 *
	 * @static
	 * @see     http://stackoverflow.com/questions/1619265 (Daren Schwneke)
	 * @access  public
	 * @param   $num       num  the number to ceil
	 * @param   $multiple  num  the multiple to ceil to
	 * @return
	 *
	 */
	public static function ceilTo($num, $multiple) 
	{
		return ceil($num / $multiple) * $multiple;
	}

	/**
	 * Returns the number floor-ed to the nearest multiple of $multiple
	 *
	 * @static
	 * @see     http://stackoverflow.com/questions/1619265 (Daren Schwneke)
	 * @access  public
	 * @param   $num       num  the number to floor
	 * @param   $multiple  num  the multiple to floor to
	 * @return
	 *
	 */
	public static function floorTo($num, $multiple) 
	{
		return floor($num / $multiple) * $multiple;
	}

	/**
	 * Returns true if argument is a valid database id
	 *
	 * A valid id is a positive, non-zero integer that's less than or equal to
	 * the maximum value for the unsigned datatype.
	 *
	 *     Num::isId('abc');            // returns false
	 *     Num::isId(123);              // returns true
	 *     Num::isId(999999, 'small');  // returns false
	 *
	 * @static
	 * @access  public
	 * @throws  InvalidArgumentException  if $size arg is not a valid option
	 * @param   $id     num  the id to test
	 * @param   $size   str  the size of the int to test (possible values include
	 *                       'tiny[int]', 'small[int]', 'medium[int]', 'int[eger]', 
	 *                       and 'big[int]') (optional; if omitted, defaults to 
	 *                       'int')
	 * @return  bool
	 *
	 */
	public static function isId($num, $size = 'int') 
	{
		$isId = false; 

		// if the id is a positive integer
		if (self::isInt($num) && $num > 0) {
			// if the id is the correct size
			switch ($size) {
				case 'tiny':
				case 'tinyint':
					$isId = ($num <= 255);
					break;
				
				case 'small':
				case 'smallint':
					$isId = ($num <= 65535);
					break;
				
				case 'medium':
				case 'mediumint':
					$isId = ($num <= 8388607);
					break;
				
				case 'int':
				case 'integer':
					$isId = ($num <= 4294967295);
					break;
				
				case 'big':
				case 'bigint':
					// cast the datatype's maximum value to a float
					// the integer's size is beyond PHP's maximum integer value
					$isId = ($num <= (float) 18446744073709551615);
					break;
			
				default:
					throw new \InvalidArgumentException(
						"isId() expects parameter two to be a valid size name ".
							"such as: 'tiny[int], 'small[int]', 'medium[int]', ".
							"'int[eger]', or 'big[int]'; $size given"
					);
			
			}
		}
		
		return $isId;
	}
	
	/**
	 * Returns true if the str is an integer
	 *
	 * PHP's native is_int() functions returns false on strings like '23' or '1'.
	 * I will evaluate those integer strings to true.
	 *
	 *     Num::isInt(1);    // returns true
	 *     Num::isInt('1');  // returns true
	 *     Num::isInt(1.1);  // returns false
	 *
	 * @static
	 * @access  public
	 * @param   $num  mixed  the number to test
	 * @return        bool
	 */
	public static function isInt($num) 
	{
		return is_numeric($num) && is_int(+$num);
	}

	/**
	 * Alias for the isInt() method
	 *
	 * @static
	 * @access  public
	 * @param   $num  mixed  the number to test
	 * @return        bool  
	 *
	 */
	public static function is_int($num)
	{
		return self::isInt($num);
	}

	/**
	 * Returns true if the number is an integer, decimal, fraction, or mixed
	 * number
	 *
	 * PHP's native is_numeric() method will return false on fractions or
	 * mixed numbers. I'll return true.
	 *
	 *     self::isNumeric(2);        // returns true
	 *     self::isNumeric(1.5);      // returns true
	 *     self::isNumeric("3/2");    // returns true
	 *     self::isNumeric("1 1/2");  // returns true
	 *
	 * @static
	 * @access  public
	 * @param   $num  num   the number to test
	 * @return        bool
	 *
	 */
	public static function isNumeric($num)
	{
		return self::val($num) !== null;
	}

	/**
	 * Alias for isNumeric()
	 *
	 * @static
	 * @access  public
	 * @param   $num  num  the number to parse
	 *
	 */
	public static function is_numeric($num) 
	{
		return self::isNumeric($num);
	}

	/**
	 * Returns true if the value is zero
	 *
	 * PHP's native empty function will consider 0, '0', 0.0, and '0.0' as empty.
	 *
	 *     Num::isZero((int) 0);      // returns true
	 *     Num::isZero((float) 0.0);  // returns true
	 *     Num::isZero("0");          // returns true
	 *     Num::isZero("0.0");        // returns true
	 *
	 * I'm useful when combined with PHP's empty() function.
	 *
	 *     $a = ! empty(0);                    // evaluates to false
	 *     $b = ! empty(0) || Num::isZero(0);  // evaluates to true
	 * 
	 * @static
	 * @access  public
	 * @param   $num  mixed  the value to test
	 * @return        bool
	 *
	 */
	public static function isZero($num) 
	{
		return $num === 0 || $num === 0.0 || $num === '0' || $num === '0.0';
	}

	/**
	 * Returns a normalized value between 0 and 1 (inclusive)
	 *
	 *     Num::normalize(1, 100);    // returns 0.01
	 *     Num::normalize(50, 100);   // returns 0.5
	 *     Num::normalize(0, 100);    // returns 0
	 *     Num::normalize(150, 100);  // returns 1
	 *
	 * @static
	 * @access  public
	 * @param   $value  num  the number to normalize
	 * @param   $max    num  the maximum to divide into $value
	 * @return          num  a number between 1 and 0 (inclusive)
	 *
	 */
	public static function normalize($value, $max)
	{
		return self::bound($value / $max, 0, 1);
	}

	/**
	 * Rounds a number to the nearest multiple of $multiple
	 *
	 * I'll round a number to the nearest multiple of $multiple. For example...
	 *
	 *     Num::roundTo(7, 2);  // returns 8
	 *     Num::roundTo(7, 4);  // returns 8
	 *     Num::roundTo(7, 8);  // returns 8
	 * 
	 * @static
	 * @see     http://stackoverflow.com/questions/1619265 (Daren Schwneke)
	 * @param   $num   num  the number to round
	 * @param   $base  num  the multiple to round to
	 * @return         num
	 *
	 */
	public static function roundTo($num, $multiple)
	{
		return round($num / $multiple) * $multiple;
	}

	/**
	 * Returns the numeric value of $str
	 *
	 * PHP does not natively support fractions, mixed numbers, or comma-separated 
	 * values, but I will. Woot!
	 *
	 *     Num::val(1);          // returns (int) 1
	 *     Num::val('1');        // returns (int) 1
	 *     Num::val(1.5);        // returns (float) 1.5
	 *     Num::val('1.5');      // returns (float) 1.5
	 *     Num::val('1 1/2');    // returns (float) 1.5
	 *     Num::val('3/2');      // returns (float) 1.5
	 *     Num::val('3\2');      // returns (float) 1.5
	 *     Num::val('1000');     // returns (int) 1000
	 *     Num::val('1,000');    // returns (int) 1000
	 *     Num::val('1,000.5');  // returns (float) 1000.5
	 *     Num::val('10000');    // returns (int) 10000
	 *     Num::val('1,0,0');    // returns null
	 *     Num::val('abc');      // returns null
	 *
	 * @static
	 * @see     http://stackoverflow.com/questions/5264143 (Pascal MARTIN)
	 *          (edited to allow back- or forward-slashes in fractions)
	 * @see     http://stackoverflow.com/questions/5917082 (Justin Morgain)
	 *          (edited to allow leading and trailing zeros in comma-separated
	 *          numbers)
	 * @access  public
	 * @param   $str  str  the integer, float, or fraction string
	 * @return        num  the string's numeric equivalent or null
	 *
	 */
	public static function val($str) 
	{
		$val = null;

		// trim le string
		$str = trim($str);

		// if the string is not a float, integer, or numeric string
		if ( ! is_numeric($str)) {
			// if the number is a valid numeric string with commas
			// apparently, this is not a straightforward thing to test in Regex
			// otherwise, if the number is a fraction or mixed number
			//
			if (preg_match('#^([1-9](?:\d*|(?:\d{0,2})(?:,\d{3})*)(?:\.\d*[0-9])?|0?\.\d*[0-9]|0)$#', $str)) {
				$val = +str_replace(',', '', $str);
			} elseif (preg_match('#^((\d+)\s+)?(\d+)[/\\\](\d+)$#', $str, $m)) {
				$val = $m[2] + $m[3] / $m[4];
			}
		} else {
			$val = +$str;
		}

		return $val;
	}
}
