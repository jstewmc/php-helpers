<?php
/**
 * An array (aka, "arr") utility class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @license    MIT License <http://opensource.org/licenses/MIT>
 * @package    Jstewmc/PhpHelpers <https://github.com/jstewmc/php-helpers>
 * @since      July 2014
 *
 */
 
namespace Jstewmc/PhpHelpers;

class Arr
{
	/**
	 * Filters an array by key
	 *
	 * I'll iterate over each key in $array passing it to the callback 
	 * function. If the callback function returns true, the current value from
	 * $array is added to the result array. Array keys are preserved.
	 *
	 *     $a = ['bar' => 'foo', 'baz' => 'quz', 'foo' => 'bar'];
	 *     $b = Arr::array_filter_key($arr, function ($k) {
	 *         return strpos($k, 'b') === 0;
	 *     });
	 * 
	 *     print_r($b);  // prints ['bar' => 'foo', 'baz' => quz']
	 *
	 * @static
	 * @see     http://php.net/manual/en/function.array-filter.php#99073
	 * @access  public
	 * @param   $input     arr       the array to filter
	 * @param   $callback  callable  the function to call for each key in $arr
	 * @return             arr
	 *
	 */
	public static function array_filter_key($array, $callback)
	{
		// if the input arr isn't empty
		if ( ! empty($array)) {
			// if the input arr is actually an arr
			if (is_array($array)) {
				// if there are keys that pass the filter
				$keys = array_filter(array_keys($array), $callback);
				if ( ! empty($keys)) {
					$array = array_intersect_key($array, array_flip($keys));
				} else {
					$array = array();
				}
			} else {
				throw new \InvalidArgumentException(
					"array_filter_key() expects paramater one to be an array, ".
						gettype($array) ." given"
				);
			}
		}

		return $array;
	}

	/**
	 * Filters an array by a key prefix
	 *
	 * I'll iterate over each key in $array. If the key starts with $prefix, 
	 * I'll add it to the result array. Array keys are preserved. 
	 *
	 * @static
	 * @access  public
	 * @param   $array   arr  the array to filter
	 * @param   $prefix  str  the key's prefix to filter
	 * @return
	 *
	 */
	public static function array_filter_key_prefix($array, $prefix)
	{
		// if the input isn't empty
		if ( ! empty($array)) {
			// if the input is an array
			if (is_array($array)) {
				// filter the array by the key's prefix
				$array = self::array_filter_key($array, function ($k) use ($prefix) {
					return strpos($k, $prefix) === 0;
				});
			} else {
				throw new \InvalidArgumentException(
					"array_filter_key_prefix() expects paramater one to be ".
						"an array, ".gettype($array)." given"
				);
			}
		}

		return $array;
	}

	/**
	 * Replaces all occurences of $search in array's keys with $replace
	 *
	 * I'll return an array with all occurences of $search in the array's keys 
	 * replaced with the given $replace value (case-insensitive).
	 *
	 * @static
	 * @access  public
	 * @see     http://us1.php.net/str_replace
	 * @param   $search   mixed  the value being searched for (aka the needle); 
	 *                           an array may be used to designate multiple 
	 *                           neeeles
	 * @param   $replace  mixed  the replacement value that replaced found
	 *                           $search values; an array may be used to
	 *                           designate multiple replacements
	 * @param   $array    arr    the array to replace
	 * @return            arr    the array with replacements
	 *
	 */
	public static function array_key_str_ireplace($search, $replace, $array) 
	{
		// if $array isn't empty
		if ( ! empty($array)) {
			// if $array is actually an array
			if (is_array($array)) {
				// flip, search/replace, and flip again
				$array = array_flip($array);	
				$array = array_map(function ($v) {
					return str_ireplace($search, $replace, $v);
				}, $array);
				$array = array_flip($array);	
			}
		}

		return $array;
	}

	/**
	 * Returns true if the $key exists in $arr and its value is not empty
	 *
	 *    $arr = ['foo' => null, 'bar' => array(), 'qux' => 'hello'];
	 *    Arr::array_key_value_exists('quux', $arr);  // returns false
	 *    Arr::array_key_value_exists('foo', $arr);   // returns false
	 *    Arr::array_key_value_exists('bar', $arr);   // returns false
	 *    Arr::array_key_value_exists('qux', $arr);   // returns true
	 * 
	 * @static
	 * @access  public
	 * @param   $key  str   the key's name
	 * @param   $arr  arr   the array to test
	 * @return        bool
	 *
	 */
	public static function array_key_value_exists($key, $arr)
	{
		return array_key_exists($key, $arr) && ! empty($arr[$key]);
	}

	/**
	 * Checks if a value exists in an array
	 *
	 * I'll search $haystack for $needle. Unlike PHP's native in_array() method,
	 * I'll accept begins-with (e.g., "foo*"), ends-with (e.g., "*foo"), and 
	 * contains (e.g., "*foo*") wildcard notation.
	 *
	 *     Arr::in_array_wildcard('foo', ['foo', 'bar']);  // returns true
	 *     Arr::in_array_wildcard('qux', ['foo', 'bar']);  // returns false
	 *     Arr::in_array_wildcard('fo*', ['foo', 'bar']);  // returns true
	 *     Arr::in_array_wildcard('*oo', ['foo', 'bar']);  // returns true
	 *     Arr::in_array_wildcard('*o*', ['foo', 'bar']);  // returns true
	 * 
	 * @static
	 * @access  public
	 * @throws  InvalidArgumentException  if $needle is not a string; or, if
	 *                                    $haystack is not an array
	 * @param   $needle    str   the needle to find
	 * @param   $haystack  arr   the haystack to search
	 * @param   $wildcard  str   the wildcard character (optional; if omitted, 
	 *                           defaults to '*')
	 * @return             bool
	 *
	 */
	public static function in_array_wildcard($needle, $haystack, $wildcard = '*') 
	{
		$inArray = false;

		// if $needle is a string
		if (is_string($needle)) {
			// if $haystack is an array
			if (is_array($haystack)) {
				// if $needle contains the wildcard character
				if (strpos($needle, $wildcard) !== false) {
					// determine if the neeedle starts or ends with the wildcard
					$startsWith = \Jstewmc\PhpHelpers\Str::startsWith($haystack, $wildcard);
					$endsWith   = \Jstewmc\PhpHelpers\Str::endsWith($haystack, $wildcard);
					// loop through the haystack
					foreach ($haystack as $value) {
						// if the needle starts and ends with the wildcard
						if ($startsWith && $endsWith) {
							// true if the value contains the substr anywhere
							$needle  = substr(substr($needle, 0, -1), 1);
							$inArray = strpos($value, $needle) !== false;
						} elseif ($startsWith) {
							// otherwise, if the needle starts with the wildcard
							// true if the value ends with the non-wildcard substr
							//
							$needle  = substr($needle, 1);
							$inArray = \Jstewmc\PhpHelpers\Str::endsWith($value, $needle);
						} else {
							// finally, if the needle ends with the wildcard
							// true if the value starts with the non-wildcard substr
							//
							$needle  = substr($needle, 0, -1);
							$inArray = \Jstewmc\PhpHelpers\Str::startsWith($value, $needle);
						}
						// if the needle is in the array, stop looking
						if ($inArray) {
							break;
						}
					}	
				} else {
					$inArray = in_array($needle, $haystack);
				}
			} else {
				throw new \InvalidArgumentException(
					'in_array_wildcard() expects parameter two to be an array, '.
						gettype($haystack) .' given'
				);
			}
		} else {
			throw new \InvalidArgumentException(
				'in_array_wildcard() expects parameter one to be a string, '.
					gettype($haystack) .' given'
			);
		}

		return $inArray;
	}

	/**
	 * Returns true if the array has at least one string key (excluding numeric strings)
	 *
	 * PHP natively treats all arrays as associative arrays. I'll consider an
	 * associative array as an array with a string key. Interally, PHP casts 
	 * string keys containing valid integers to integer type (e.g., "8" will be
	 * stored as 8).
	 *
	 * For example...
	 *    is_assoc([1, 2, 3]);                       // returns false
	 *    is_assoc(['foo', 'bar', 'baz']);           // returns false
	 *    is_assoc(['1' => 'foo', 2 => 'bar']);      // returns false
	 *    is_assoc(['1' => 'foo', '8' => 'bar']);    // returns false
	 *    is_assoc(['1' => 'foo', 'bar' => 'baz']);  // returns true
	 *
	 * @access  public
	 * @throws  InvalidArgumentException  if $array param is not an arr
	 * @param   $array  arr   the array to test
	 * @return          bool
	 *
	 */
	public function is_assoc($array) 
	{
		// if $array isn't empty
		if ( ! empty($array)) {
			// if $array is actually an array
			if (is_array($array)) {
				$is_assoc = (bool) count(array_filter(array_keys($array), 'is_string'));
			} else {
				throw new \InvalidArgumentException(
					"is_assoc() expects parameter one to be an array".
						gettype($array)." given"
				);
			}
		} else {
			$is_assoc = false;
		}

		return $is_assoc;
	}

	/**
	 * Sorts an array of assoc arrays by a field's value
	 *
	 * Oftentimes, you have a 0-indexed array of associative arrays. For example, 
	 * a SELECT sql query result or a display-friendly data array. I'll sort a 
	 * 0-based array of associative arrays ascending by a field's value in the 
	 * associative array.
	 * 
	 *   $a = [['foo' => 'c'], ['foo' => 'a'], ['foo' => 'b']];
	 *   $b = Arr::usort_field($a, 'foo');
	 *
	 *   print_r($b);  // prints [['foo' => 'a'], ['foo' => 'b'], ['foo' => 'c']]
	 * 
	 * @static
	 * @param   $arr    arr  the array of assoc arrays to sort
	 * @param   $field  str  the field's name
	 * @param   $sort   str  the sort order (possible values 'asc[ending]' or 
	 *                       'desc[ending]) (optional; if omitted, defaults to
	 *                       'asc') (case-insensitive)
	 * @return          arr  the sorted array
	 *
	 */
	public static function usort_field($arr, $field, $sort = 'asc')
	{
		// sort the array using the field's value in ascending order
		usort($arr, function ($a, $b) use ($field) {
			if ($a[$field] < $b[$field]) {
				return -1;
			} elseif ($a[$field] > $b[$field]) {
				return 1;
			} else {
				return 0;
			}
		});

		// if the sort order is descending
		$sort = strtolower($sort);
		if ($sort === 'desc' || $sort === 'descending') {
			$arr = array_reverse($arr);
		}

		return $arr;
	}

	/**
	 * Sorts an array of objects using a public property's value
	 *
	 * @static
	 * @access  public
	 * @param   $arr       arr  the array of objects to sort
	 * @param   $property  str  the object's public property name
	 * @param   $sort      str  the sort order (possible values 'asc[ending]' or 
	 *                         'desc[ending]) (optional; if omitted, defaults to
	 *                         'asc') (case-insensitive)
	 * @return             arr  the sorted array
	 *
	 */
	public static function usort_property($arr, $property, $sort = 'asc') 
	{
		// sort the array using the objects' property in ascending order
		usort($arr, function ($a, $b) use ($property) {
			if ($a->$property < $b->$property) {
				return -1;
			} elseif ($a->$property > $b->$property) {
				return 1;
			} else {
				return 0;
			}
		});

		// if the sort is descending
		$sort = strtolower($sort);
		if ($sort === 'desc' || $sort === 'descending') {
			$arr = array_reverse($arr);
		}

		return $arr;
	}

	/**
	 * Sorts an array of objects using a method's return value
	 *
	 * @static
	 * @access  public
	 * @param   $arr     arr  the arr to sort
	 * @param   $method  str  the public method to use
	 * @param   $sort    str  the sort order (possible values 'asc[ending]' or 
	 *                        'desc[ending]) (optional; if omitted, defaults to
	 *                        'asc') (case-insensitive)  
	 * @return           arr  the sorted array
	 *
	 */
	public static function usort_method($arr, $method, $sort = 'asc') 
	{
		// sort the array using the objects' method in ascending order
		usort($arr, function ($a, $b) use ($method) {
			if ($a->$method() < $b->$method()) {
				return -1;
			} elseif ($a->$method() > $b->$method()) {
				return 1;
			} else {
				return 0;
			}
		});

		// if the sort order is descending
		$sort = strtolower($sort);
		if ($sort === 'desc' || $sort === 'descending') {
			$arr = array_reverse($arr);
		}

		return $arr;
	}
}
