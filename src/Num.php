<?php
/**
 * The file for the Num class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2014 Jack Clayton
 * @license    MIT License <http://opensource.org/licenses/MIT>
 */

namespace Jstewmc\PhpHelpers;

/** 
 * The number (aka, "num") class
 *
 * Keep in mind, a number in PHP (and hereafter in this class documentation) is 
 * considered to be a (float), (int), or numeric (string). 
 *
 * @since  0.1.0  
 */
class Num 
{
	/* !Constants */
	
	/**
	 * @var  the regex for a comma-separated number (e.g., "1,000")
	 */
	const REGEX_NUMBER_COMMA = '#^([1-9](?:\d*|(?:\d{0,2})(?:,\d{3})*)(?:\.\d*[0-9])?|0?\.\d*[0-9]|0)$#';
	
	/**
	 * @var  the regex for a mixed number (e.g., "1 1/2")
	 */
	const REGEX_NUMBER_MIXED = '#^((\d+)\s+)?(\d+)[/\\\](\d+)$#';
	
	
	/* !Public members */
	
	/**
	 * @var  array  an array of cardinal numbers (e.g., "one", "two", etc)
	 */
	public static $cardinals = array(
		'one'       => 1, 
		'two'       => 2,
		'three'     => 3,
		'four'      => 4,
		'five'      => 5,
		'six'       => 6,
		'seven'     => 7,
		'eight'     => 8,
		'nine'      => 9,
		'ten'       => 10,
		'eleven'    => 11,
		'twelve'    => 12,
		'thirteen'  => 13,
		'fourteen'  => 14,
		'fifteen'   => 15,
		'sixteen'   => 16,
		'seventeen' => 17,
		'eighteen'  => 18,
		'nineteen'  => 19,
		'twenty'    => 20,
		'thirty'    => 30,
		'forty'     => 40,
		'fifty'     => 50,
		'sixty'     => 60,
		'seventy'   => 70,
		'eighty'    => 80,
		'ninety'    => 90
	);
	
	/**
	 * @var  array  an array of ordinal numbers (e.g., "first", "second", etc)
	 */
	public static $ordinals = array(
		'first'       => 1,
		'second'      => 2,
		'third'       => 3,
		'fourth'      => 4,
		'fifth'       => 5,
		'sixth'       => 6,
		'seventh'     => 7,
		'eighth'      => 8,
		'nineth'      => 9,
		'tenth'       => 10,
		'eleventh'    => 11,
		'twelveth'    => 12,
		'thirteenth'  => 13,
		'fourteenth'  => 14,
		'fifteenth'   => 15,
		'sixteenth'   => 16,
		'seventeenth' => 17,
		'eighteenth'  => 18,
		'nineteenth'  => 19,
		'twentieth'   => 20,
		'thirtieth'   => 30,
		'fourtieth'   => 40,
		'fiftieth'    => 50,
		'sixtieth'    => 60,
		'seventieth'  => 70,
		'eightieth'   => 80,
		'ninetieth'   => 90
	);
	
	/**
	 * @var  array  an array of powers
	 */
	public static $powers = array(
		'hundred'  => 100,
		'thousand' => 1000,
		'million'  => 1000000,
		'billion'  => 1000000000
	);
	
	/**
	 * @var  array  an array of number suffixes
	 */
	public static $suffixes = array('th', 'st', 'nd', 'rd');
	
	
	/* !Public methods */
	
	/**
	 * Returns true if float $a is almost equal to float $b
	 *
	 * Floating point numbers should never be compared for equivalence because of the
	 * way they are stored internally. They have limited precision, and many numbers
	 * numbers that are representable as floating point numbers in base 10 (e.g., 0.1 
	 * or 0.7) do not have an exact representation as floating point numbers in base 2.
	 *
	 * To test floating point values for equality, an upper bound on the relative error 
	 * due to rounding is used. This value is known as the machine epsilon, and is the 
	 * largest acceptable difference in calculations (exclusive).
	 * 
	 * @since  0.1.0
	 *
	 * @param  int|float  $a        the first value
	 * @param  int|float  $b        the second value
	 * @param  int|float  $epsilon  the maximum allowed difference (exclusive) (optional; 
	 *    if omitted defaults to 0.00001)
	 *
	 * @return  bool  true if the values are equal
	 *
	 * @throws  \BadMethodCallException    if $a, $b, or $epsilon is null
	 * @throws  \InvalidArgumentException  if $a is not a number
	 * @throws  \InvalidArgumentException  if $b is not a number
	 * @throws  \InvalidArgumentException  if $epsilon is not a number
	 * @throws  \InvalidArgumentException  if $epsilon is not greater than zero
	 *
	 * @see  http://www.php.net/manual/en/language.types.float.php  man page on float type
	 */
	public static function almostEqual($a, $b, $epsilon = 0.00001) 
	{
		$isEqual = false;
		
		// if $a, $b, and $epsilon are not empty
		if ($a !== null && $b !== null && $epsilon !== null) {
			// if $a is a number
			if (is_numeric($a)) {
				// if $b is a number
				if (is_numeric($b)) {
					// if $epsilon is a number
					if (is_numeric($epsilon)) {
						// if $epsilon is greater than zero
						if ($epsilon > 0) {
							// roll it
							$isEqual = (abs($a - $b) < $epsilon);
						} else {
							throw new \InvalidArgumentException(
								__METHOD__." expects the third parameter, epsilon, to be greater than zero"
							);
						}
					} else {
						throw new \InvalidArgumentException(
							__METHOD__." expects the third parameter, epsilon, to be a number"
						);
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__." expects the second parameter, b, to be a number"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__." expects the first parameter, a, to be a number"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__." expects two or three numeric arguments"
			);
		}
		
		return $isEqual;
	}

	/**
	 * Bounds a value between an upper and/or lower bound (inclusive)
	 *
	 * I'll bound $number between a lower an upper bound; greater than or equal to a lower 
	 * bound; or, less than or equal to an upper bound.
	 *
	 * For example:
	 *
	 *     Num::bound(1, 0);         // returns 1
	 *     Num::bound(0, 1);         // returns 1
	 *     Num::bound(-1, null, 2);  // returns -1
	 *     Num::bound(3, null, 2);   // returns 2
	 *     Num::bound(2, 1, 3);      // returns 2
	 *     Num::bound(4, 1, 3);      // returns 3
	 * 
	 * @since  0.1.0
	 *
	 * @param  int|float  $number  the number to bound
	 * @param  int|float  $lower   the number's lower bound (inclusive)
	 * @param  int|float  $upper   the number's upper bound (inclusive)
	 *
	 * @return  int|float           the bounded value or false
	 *
	 * @throws  \BadMethodCallException    if $number and $lower and/or $upper are not passed
	 * @throws  \InvalidArgumentException  if $lower is passed and not a number
	 * @throws  \InvalidArgumentException  if $upper is passed and not a number
	 * @throws  \InvalidArgumentException  if $upper is not greater than or equal to $lower
	 */
	public static function bound($number, $lower = null, $upper = null) 
	{
		$bounded = false;
		
		// if $number and $lower and/or $upper is given
		if ($number !== null && ($lower !== null || $upper !== null)) {
			// if $number is a number
			if (is_numeric($number)) {
				// figure out which arguments were given
				$hasLower = $lower !== null;
				$hasUpper = $upper !== null;
				// if $lower is omitted or it is a valid number
				if ( ! $hasLower || is_numeric($lower)) {
					// if $upper is omitted or it is a valid number
					if ( ! $hasUpper || is_numeric($upper)) {
						// if $lower argument is omitted or $upper argument is omitted or $upper is 
						//     greater than $lower
						//
						if ( ! $hasLower || ! $hasUpper || $upper >= $lower) {
							// bound the value
							if ($hasLower && $hasUpper) {
								$bounded = min(max($number, $lower), $upper);
							} elseif ($hasLower) {
								$bounded = max($number, $lower);
							} elseif ($hasUpper) {
								$bounded = min($number, $upper);	
							}
						} else {
							throw new \InvalidArgumentException(
								__METHOD__." expects the third parameter, the upper bound ({$upper}), to be ".
									"greater than or equal to the second parameter, the lower bound ({$lower})"
							);
						}
					} else {
						throw new \InvalidArgumentException(
							__METHOD__." expects the third parameter, the upper bound, to be a number"
						);
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__." expects the second parameter, the lower bound, to be a number"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__." expects the first parameter, number, to be a number"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__." expects two or three numeric parameters"
			);
		}

		return $bounded;
	}

	/**
	 * Returns $number ceiling-ed to the nearest $multiple
	 *
	 * For example:
	 *
	 *     Num::ceilTo(5, 2);    // returns 6
	 *     Num::ceilTo(15, 10);  // returns 20
	 *     Num::ceilTo(25, 40);  // returns 40
	 *
	 * @since  0.1.0
	 *
	 * @param  int|float  $number    the number to ceil
	 * @param  int|float  $multiple  the multiple to ceil to (optional; if omitted,
	 *    defaults to 1 (aka, PHP's native ceil() function))
	 *
	 * @return  int|float  the ceiling-ed number
	 *
	 * @throws  \BadMethodCallException
	 * @throws  \InvalidArgumentException  if $number or $multiple is null
	 * @throws  \InvalidArgumentException  if $number is not a number
	 * @throws  \InvalidArgumentException  if $multiple is not a number
	 * @throws  \InvalidArgumentException  if $multiple is not greater than zero
	 *
	 * @see  http://stackoverflow.com/a/1619284  Daren Schwneke's answer to "How to
	 *    round up a number to the nearest 10?" on StackOverflow
	 */
	public static function ceilTo($number, $multiple = 1) 
	{
		$ceiled = false;
		
		// if $number and $multiple are passed
		// keep in mind, PHP's empty() will return true on zero
		// 
		if ($number !== null && $multiple !== null) {
			// if $number is actually a number
			if (is_numeric($number)) {
				// if $multiple is actually a number
				if (is_numeric($multiple)) {
					// if $multiple is greater than zero
					if ($multiple > 0) {
						// roll it
						$ceiled = ceil($number / $multiple) * $multiple;
					} else {
						throw new \InvalidArgumentException(
							__METHOD__." expects parameter two, the multiple, to be greater than zero"
						);
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__." expects parameter two, the multiple, to be a number"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__." expects parameter one, the number, to be a number"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__." expects one or two numeric parameters"
			);
		}
		
		return $ceiled;
	}

	/**
	 * Returns $number floor-ed to the nearest $multiple
	 *
	 * For example:
	 *
	 *     Num::floorTo(19, 10);     // returns 10
	 *     Num::floorTo(0.99, 0.5);  // returns 0.5
	 *     Num::floorTo(101, 100);   // returns 100
	 *
	 * @since  0.1.0
	 *
	 * @param  int|float  $number    the number to floor
	 * @param  int|float  $multiple  the multiple to floor to (optional; if omitted,
	 *    defaults to 1 (aka, PHP's native floor() function))
	 *
	 * @return  int|float
	 *
	 * @throws  \BadMethodCallException    if $number or $multiple is null 
	 * @throws  \InvalidArgumentException  if $number is not a number
	 * @throws  \InvalidArgumentException  if $multiple is not a number
	 * @throws  \InvalidArgumentException  if $multiple is not greater than zero
	 *
	 * @see  http://stackoverflow.com/a/1619284  Daren Schwneke's answer to "How to
	 *    round up a number to the nearest 10?" on StackOverflow
	 */
	public static function floorTo($number, $multiple = 1) 
	{
		$floored = false;
		
		// if $number and $multiple are not null
		if ($number !== null && $multiple !== null) {
			// if $number is actually a number
			if (is_numeric($number)) {
				// if $multiple is actually a number
				if (is_numeric($multiple)) {
					// if $multiple is greater than zero
					if ($multiple > 0) {
						// roll it
						$floored = floor($number / $multiple) * $multiple;
					} else {
						throw new \InvalidArgumentException(
							__METHOD__." expects the second parameter, the multiple, to be greater than zero"
						);
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__." expects the second parameter, the multiple, to be numeric"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__." expects the first parameter, the number, to be numeric"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__." expects one or two numeric parameters"
			);
		}
		
		return $floored;
	}
	
	/**
	 * Alias for the isInt() method
	 *
	 * @since  0.1.0
	 *
	 * @see  \Jstewmc\PhpHelpers\Num::isInt()
	 */
	public static function is_int($number)
	{
		return self::isInt($number);
	}
	
	/**
	 * Alias for the isNumeric() method
	 *
	 * @since  0.1.0
	 *
	 * @see  \Jstewmc\PhpHelpers\Num::isNumeric()
	 */
	public static function is_numeric($number) 
	{
		return self::isNumeric($number);
	}

	/**
	 * Returns true if $number is a valid database id (aka, unsigned int)
	 *
	 * A valid id is a positive, non-zero integer that's less than or equal to
	 * the maximum value for the unsigned datatype.
	 *
	 * For example:
	 *
	 *     Num::isId('abc');            // returns false
	 *     Num::isId(1.5);              // returns false
	 *     Num::isId(123);              // returns true
	 *     Num::isId(999999, 'small');  // returns false
	 *
	 * @since  0.1.0
	 *
	 * @param  int|float  $number    the number to test
	 * @param  string     $datatype  the column datatype name (possible values are
	 *    'tiny[int]', 'small[int]', 'medium[int]', 'int[eger]', and 'big[int]') 
	 *    (case-insensitive) (optional; if omitted, defaults to 'int')
	 *
	 * @return  bool  true if the number is a valid database id
	 *
	 * @throws  \BadMethodCallException    if $datatype is null
	 * @throws  \InvalidArgumentException  if $datatype is not a string
	 * @throws  \InvalidArgumentException  if $datatype is not an allowed value
	 */
	public static function isId($number, $datatype = 'int') 
	{
		$isId = false; 
		
		// if $datatype is not null
		if ($datatype !== null) {
			// if $datatype is a string
			if (is_string($datatype)) {
				// if $number is actually a number
				if (is_numeric($number)) {
					// if the number is a positive integer
					if (self::isInt($number) && $number > 0) {
						// if the number is LTE the datatype's max value
						switch (strtolower($datatype)) {
							case 'tiny':
							case 'tinyint':
								$isId = ($number <= 255);
								break;
							
							case 'small':
							case 'smallint':
								$isId = ($number <= 65535);
								break;
							
							case 'medium':
							case 'mediumint':
								$isId = ($number <= 8388607);
								break;
							
							case 'int':
							case 'integer':
								$isId = ($number <= 4294967295);
								break;
							
							case 'big':
							case 'bigint':
								// cast the datatype's maximum value to a float
								// the integer's size is beyond PHP's maximum integer value
								//
								$isId = ($number <= (float) 18446744073709551615);
								break;
						
							default:
								throw new \InvalidArgumentException(
									__METHOD__." expects parameter two to be a valid datatype name such as: ".
										"'tiny[int]', 'small[int]', 'medium[int]', 'int[eger]', or 'big[int]',".
										"{$datatype} given"
								);
						}
					}
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__." expects parameter two to be a string, the datatype name"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__."() expects two parameters, a number and a datatype name"
			);
		}
		
		return $isId;
	}
	
	/**
	 * Returns true if $number is an integer or integer string
	 *
	 * PHP's native is_int() functions returns false on strings like '23' or '1'.
	 * I will evaluate those integer strings to true.
	 *
	 * For example:
	 *
	 *     is_int(1);        // returns true
	 *     Num::isInt(1);    // returns true
	 *
	 *     is_int('1');      // returns false
	 *     Num::isInt('1');  // returns true
	 *
	 * @since  0.1.0
	 *
	 * @param  int|float  $number  the number to test
	 *
	 * @return  bool  true if $number is an integer or integer string
	 */
	public static function isInt($number) 
	{	
		return is_numeric($number) && is_int(+$number);
	}

	/**
	 * Returns true if $number is an integer, decimal, fraction, or mixed
	 * number
	 *
	 * PHP's native is_numeric() method will return false on fractions or
	 * mixed numbers. I'll return true.
	 *
	 * For example:
	 *
	 *     is_numeric(2);             // returns true
	 *     self::isNumeric(2);        // returns true
	 *
	 *     is_numeric(1.5);           // returns true
	 *     self::isNumeric(1.5);      // returns true
	 *
	 *     is_numeric("1.5");         // returns true
	 *     self::isNumeric("1.5");    // returns true
	 * 
	 *     is_numeric("1/2");         // returns false
	 *     self::isNumeric("1/2");    // returns true
	 *
	 *     is_numeric("1 1/2");       // returns false
	 *     self::isNumeric("1 1/2");  // returns true
	 *
	 * @since  0.1.0
	 *
	 * @param  mixed  $number  the number to test
	 *
	 * @return  bool  true if $number is a number
	 *
	 */
	public static function isNumeric($number)
	{	
		return is_numeric($number)
			|| (is_string($number) && preg_match(self::REGEX_NUMBER_MIXED, $number))
			|| (is_string($number) && preg_match(self::REGEX_NUMBER_COMMA, $number));
	}

	/**
	 * Returns true if the value is zero
	 *
	 * It can be a little tricky to evaluate a variable in PHP as zero. If you use 
	 * PHP's native empty function, it will consider 0, '0', 0.0, and '0.0' as empty.
	 * However, it will also consider false, array(), '' as empty too. If you compare
	 * a value to the possible values for zero in PHP over-and-over, it's a pain. I
	 * make it easy.
	 *
	 * For example:
	 *
	 *     Num::isZero((int) 0);      // returns true
	 *     Num::isZero((float) 0.0);  // returns true
	 *     Num::isZero("0");          // returns true
	 *     Num::isZero("0.0");        // returns true
	 *
	 * I'm particularly useful when combined with PHP's empty() function.
	 *
	 *     $a = ! empty(0);                    // evaluates to false
	 *     $b = ! empty(0) || Num::isZero(0);  // evaluates to true
	 * 
	 * @since  0.1.0
	 *
	 * @param  mixed  $number  the number to test
	 *
	 * @return  bool  true if $number is zero
	 */
	public static function isZero($number) 
	{
		return $number === 0 
			|| $number === 0.0 
			|| $number === '0' 
			|| $number === '0.0';
	}

	/**
	 * Returns a normalized value between 0 and 1 (inclusive)
	 *
	 * In statistics, it's often helpful to normalize (sometimes referred to as index) 
	 * values for comparison. For example, for a retailer to compare an item's revenue
	 * against its quantity-in-stock, the values must be normalized (otherwise, revenue
	 * will likely be in the thousands of dollars and quantity in stock will likely be
	 * in the dozens).
	 *
	 * If you divide every item's revenue by the maximum revenue of any item, you'll have 
	 * a normalized value between 1 and 0. If you do the same for quantity-in-stock, you
	 * can compare the two values easily.
	 * 
	 * For example:
	 *
	 *     Num::normalize(1, 100);    // returns 0.01
	 *     Num::normalize(50, 100);   // returns 0.5
	 *     Num::normalize(0, 100);    // returns 0
	 *     Num::normalize(150, 100);  // returns 1
	 *
	 * @since   0.1.0
	 *
	 * @param   int|float  $number  the number to normalize
	 * @param   int|float  $max     the maximum to divide into $value
	 *
	 * @return  int|float  a number between 1 and 0 (inclusive)
	 *
	 * @throws  \BadMethodCallException    if $number or $max are not passed
	 * @throws  \InvalidArgumentException  if $number is not a number
	 * @throws  \InvalidArgumentException  if $max is not a number
	 * @throws  \InvalidArgumentException  if $max is not greater than zero
	 */
	public static function normalize($number, $max)
	{
		$norm = false;
		
		// if $number and $max are given
		if ($number !== null && $max !== null) {
			// if $number is actually a number
			if (is_numeric($number)) {
				// if $max is a number
				if (is_numeric($max)) {
					// if $max is greater than zero
					if ($max > 0) {
						// bound the quotient between 0 and 1
						$norm = self::bound($number / $max, 0, 1);		
					} else {
						throw new \InvalidArgumentException(
							__METHOD__." expects parameter two, the max, to be greater than zero"
						);
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__." expects parameter two, the max, to be a number"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__." expects parameter one, the number, to be a number"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__." expects two numeric parameters"
			);
		}
		
		return $norm;
	}

	/**
	 * Rounds $number to the nearest $multiple
	 *
	 * For example:
	 *
	 *     Num::roundTo(7, 2);  // returns 8
	 *     Num::roundTo(7, 4);  // returns 8
	 *     Num::roundTo(7, 8);  // returns 8
	 *
	 * @since  0.1.0
	 *
	 * @param  int|float  $number   the number to round
	 * @param  int|float  $multiple the multiple to round to (optional; if omitted,
	 *    defaults to 1 (aka, PHP's native round() method))
	 *
	 * @return  int|float  the rounded number
	 *
	 * @see  http://stackoverflow.com/a/1619284  Daren Schwneke's answer to "How to
	 *    round up a number to the nearest 10?" on StackOverflow
	 */
	public static function roundTo($number, $multiple = 1)
	{
		$round = false;
		
		// if $number and $multiple exist
		if ($number !== null && $multiple !== null) {
			// if $number is actually a number
			if (is_numeric($number)) {
				// if $multiple is actually a number
				if (is_numeric($multiple)) {
					// if $multiple is greater than zero
					if ($multiple > 0) {
						// roll it
						$round = round($number / $multiple) * $multiple;
					} else {
						throw new \InvalidArgumentException(
							__METHOD__." expects parameter two, the multiple, to be greater than zero"
						);
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__." expects parameter two, the multiple, to be a number"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__." expects parameter one, the number, to be a number"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__." expects one or two numeric parameters"
			);
		}
		
		return $round;
	}

	/**
	 * Returns the numeric value of $var
	 *
	 * PHP does not natively support fractions, mixed numbers, comma-separated 
	 * values, ordinals, or cardinals values, but I will. Woot! 
	 *
	 * I use the following rules:
	 *
	 *     Bools
	 *         Bools are returns as strictly typed integers.
	 *
	 *     Integers
	 *         Integers are returned as strictly typed integers.
	 *
	 *     Floats
	 *         Floats are returned as strictly typed floats.
	 *
	 *     Strings
	 *         Numeric strings are returned as their strictly typed equivalent (i.e.,
	 *         an integer or float). Numeric strings with commas are returned as their 
	 *         strictly typed equivalents. Fractions and mixed numbers are returned as
	 *         floats. Ordinal and cardinal numbers (e.g., "one hundred", "second" or 
	 *         "2nd") are returned as integers. All other strings return 0.
	 *
	 *     Arrays
	 *         Empty arrays return 0, and non-empty arrays return 1.
	 *
	 *     Objects
	 *         This method should not be used on objects. However, unlike the native
	 *         PHP intval() or floatval() methods, I will not raise an error. I will 
	 *         always evaluate objects as 1.
	 *
	 * For example:
	 *
	 *     Num::bool(true);           // returns (int) 1
	 *     Num::val(1);               // returns (int) 1
	 *     Num::val('1');             // returns (int) 1
	 *     Num::val(1.5);             // returns (float) 1.5
	 *     Num::val('1.5');           // returns (float) 1.5
	 *     Num::val('1 1/2');         // returns (float) 1.5
	 *     Num::val('3/2');           // returns (float) 1.5
	 *     Num::val('3\2');           // returns (float) 1.5
	 *     Num::val('1000');          // returns (int) 1000
	 *     Num::val('1,000');         // returns (int) 1000
	 *     Num::val('1,000.5');       // returns (float) 1000.5
	 *     Num::val('10000');         // returns (int) 10000
	 *     Num::val('1st);            // returns (int) 1
	 *     Num::val('second');        // returns (int) 2
	 *     Num::val('one hundred');   // returns (int) 100
	 *     Num::val('1,0,0');         // returns 0
	 *     Num::val('abc');           // returns 0
	 *     Num::val(array());         // returns 0
	 *     Num::val(array('foo'));    // returns 1
	 *     Num::val(new stdClass());  // returns 1
	 *
	 * @since  0.1.0
	 *
	 * @param  mixed  $var  the value to evaluate
	 *
	 * @return  int|float  the value's numeric equivalent
	 *
	 * @see  http://stackoverflow.com/a/5264255  Pascal MARTIN's answer to "Convert
	 *    mixed fraction string to float in PHP" on StackOverflow (edited to allow 
	 *    back- or forward-slashes in fractions)
	 * @see  http://stackoverflow.com/a/5917250  Justin Morgain's answer to "Regular
	 *    expression to match numbers with or without commas and decimals in text" on
	 *    StackOverflow (edited to allow leading and trailing zeros in comma-separated
	 *    numbers)
	 * @see  http://stackoverflow.com/a/11219737  El Yobo's answer to "Converting
	 *    words to numbers in PHP" on StackOverflow (edited to use static arrays of
	 *    cardinals, ordinals, and powers and to use intval() instead of floatval())
	 */
	public static function val($var) 
	{
		$value = false;
		
		// if $var is a string, trim it
		if (is_string($var)) {
			$var = trim($var);	
		}

		// if the string is not already a (float), (integer), or numeric (string)
		if ( ! is_numeric($var)) {
			// if the number is a string
			if (is_string($var)) {
				// if the number is a number with commas (e.g., "1,000")
				// else, if the number is a fraction or mixed number (e.g., "1/2")
				// else, if the number has a suffix (e.g., "1st")
				// else, if the number is the name for a number  (e.g., "one hundred")
				// otherwise, it's zero
				//
				if (preg_match(self::REGEX_NUMBER_COMMA, $var)) {
					$value = +str_replace(',', '', $var);
				} elseif (preg_match(self::REGEX_NUMBER_MIXED, $var, $m)) {
					$value = $m[2] + $m[3] / $m[4];
				} elseif (is_numeric(substr($var, 0, 1)) && in_array(substr($var, -2), self::$suffixes)) {
					$value = substr($var, 0, -2);
				} else {
					// if the string is composed *only* of valid number names
					//
					// first, lowercase $var, strip commas, and replace "-" and " and " with spaces
					// then, explode on space, trim, and filter out empty values 
					// finally, merge all the possible numeric string values together
					//
					$words = strtolower($var);
					$words = str_ireplace(',', '', $words);
					$words = str_ireplace(array('-', ' and '), ' ', $words);
					$words = array_filter(array_map('trim', explode(' ', $words)));
					$names = array_merge(
						array_keys(self::$cardinals),
						array_keys(self::$ordinals),
						array_keys(self::$powers)
					);
					if (count(array_diff($words, $names)) === 0) {
						// replace the words with their numeric values
						$var = strtr(
							strtolower($var),
							array_merge(
								self::$cardinals, 
								self::$ordinals,
								self::$powers,
								array('and' => '')
							)
						);
						// convert the numeric values to integers
					    $parts = array_map(
					        function ($val) {
					            return intval($val);
					        },
					        preg_split('/[\s-]+/', $var)
					    );
					
					    $stack = new \SplStack();  // the current work stack
					    $sum   = 0;               // the running total
					    $last  = null;            // the last part
					
						// loop through the parts
					    foreach ($parts as $part) {
					    	// if the stack isn't empty
					        if ( ! $stack->isEmpty()) {
					            // we're part way through a phrase
					            if ($stack->top() > $part) {
					                // decreasing step, e.g. from hundreds to ones
					                if ($last >= 1000) {
					                    // If we drop from more than 1000 then we've finished the phrase
					                    $sum += $stack->pop();
					                    // This is the first element of a new phrase
					                    $stack->push($part);
					                } else {
					                    // Drop down from less than 1000, just addition
					                    // e.g. "seventy one" -> "70 1" -> "70 + 1"
					                    $stack->push($stack->pop() + $part);
					                }
					            } else {
					                // Increasing step, e.g ones to hundreds
					                $stack->push($stack->pop() * $part);
					            }
					        } else {
					            // This is the first element of a new phrase
					            $stack->push($part);
					        }
					
					        // Store the last processed part
					        $last = $part;
					    }
					
					    $value = $sum + $stack->pop();
					} else {
						$value = 0;	
					}
				}
			} elseif (is_array($var)) {
				$value = min(count($var), 1);
			} elseif (is_object($var)) {
				$value = 1;
			} elseif (is_bool($var)) {
				$value = (int) $var;
			}
		} else {
			$value = +$var;
		}

		return $value;
	}
}
