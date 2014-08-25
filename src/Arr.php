<?php
/**
 * The file for the Arr class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2014 Jack Clayton
 * @license    MIT License <http://opensource.org/licenses/MIT>
 * @package    Jstewmc\PhpHelpers <https://github.com/jstewmc/php-helpers>
 */
 
namespace Jstewmc\PhpHelpers;

/**
 * A class of array (aka, "arr") utility functions
 *
 * @since 0.1.0
 */
class Arr
{	
	/**
	 * Returns true if $key does not exist in $array or $array[$key] is empty
	 *
	 * PHP's isset() method is will return false if the key does not exist or if the 
	 * key exists and its value is null. However, it will return true if the key
	 * exists and its value is not null (including other "empty" values like '', false
	 * and array()). 
	 *
	 * PHP's empty() method (or some frameworks) will throw a warning if you attempt
	 * to test a non-existant key in an array.
	 *
	 * I, on the other hand, will return false if the key does not exist in the array
	 * or if the key's value is empty.
	 *
	 * For example:
	 *
	 *    $a = ['foo' => null, 'bar' => array(), 'qux' => 'hello'];
	 *
	 *    // when key doesn't exist (!)
	 *    isset($a['quux']);         // returns false
	 *    ! empty($a['quux']);       // throws key-does-not-exist warning
	 *    ! Arr::empty('quux', $a);  // returns false
	 *
	 *    // when key does exist, but value is null
	 *    isset($a['foo']);         // returns false
	 *    ! empty($a['foo']);       // returns false
	 *    ! Arr::empty('foo', $a);  // returns false
	 *
	 *    // when key does exist, but value is "empty" (!)
	 *    isset($a['bar']);         // returns true
	 *    ! empty($a['bar']);       // returns false
	 *    ! Arr::empty('bar', $a);  // returns false
	 *
	 *    // when key does exist, but value is not "empty"
	 *    isset($a['qux']);         // returns true
	 *    ! empty($a['qux']);       // returns true
	 *    ! Arr::empty('qux', $a);  // returns true
	 * 
	 * @since   0.1.0
	 * @param   string  $key          the key's name
	 * @param   array   $array        the array to test
	 * @param   bool    $isZeroEmpty  a flag indicating whether or not zero is
	 *     considered empty (optional; if omitted, defaults to true - i.e., the
	 *     default behavior of PHP's empty() function )
	 * @return  bool  true if the key exists and its value is not empty
	 * @throws  \BadMethodCallException    if $key or $array are null
	 * @throws  \InvalidArgumentException  if $key is not a string
	 * @throws  \InvalidArgumentException  if $array is not an array
	 * @throws  \InvalidArgumentException  if $isZeroEmpty is not a bool value
	 */
	public static function empty($key, $array, $isZeroEmpty = true)
	{
		$empty = false;
		
		// if $key and array are given
		if ($key !== null && $array !== null) {
			// if $key is a string
			if (is_string($key)) {
				// if $array is an array
				if (is_array($array)) {
					// if $zero is a bool value
					if (is_bool($isZeroEmpty)) {
						// if $array is not empty
						if ( ! empty($array)) {
							// if the key exists
							if (array_key_exists($key, $array)) {
								$empty = empty($array[$key]);
								// if the value is "empty" but zero is not considered empty
								if ($empty && ! $isZeroEmpty) {
									// if the value is zero it is not empty
									$empty = ! \Jstewmc\PhpHelpers\Num::isZero($array[$key]);
								}
							}
						}
					} else {
						throw new \InvalidArgumentException(
							__METHOD__."() expects parameter three, allow zeros, to be a bool"
						);
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__."() expects parameter two, array, to be an array"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__."() expects parameter one, key, to be a string key name"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__."() expects two parameters, a string key name and an array"
			);
		}
		
		return $empty;
	}
	
	/**
	 * Filters an array by key
	 *
	 * I'll iterate over each key in $array passing it to the $callback 
	 * function. If the callback function returns true, the current value from
	 * $array is added to the result array. Array keys are preserved.
	 *
	 * For example:
	 *
	 *     $a = ['foo', 'bar', 'baz', 'qux'];
	 *     $b = Arr::array_filter_key($a, function ($k) {
	 *         return $k > 1;
	 *     });
	 *     print_r($b);  // prints ['baz', 'qux']
	 *
	 * @since   0.1.0
	 * @param   array     $input     the array to filter
	 * @param   callback  $callback  the function to call for each key in $arr
	 * @return  array                the filtered array
	 * @throws  \BadMethodCallException    if $array or $callback are null
	 * @throws  \InvalidArgumentException  if $array is not an array
	 * @throws  \InvalidArgumentException  if $callback is not a callable function
	 * @see     <http://php.net/manual/en/function.array-filter.php#99073>
	 */
	public static function filterBykey($array, $callback)
	{
		$filtered = array();
		
		// if $array and $callback are given
		if ($array !== null && $callback !== null) {
			// if the input arr is actually an arr
			if (is_array($array)) {
				// if $callback is callable
				if (is_callable($callback)) {
					// if $array is not empty
					if ( ! empty($array)) {
						// if there are keys that pass the filter
						$keys = array_filter(array_keys($array), $callback);
						if ( ! empty($keys)) {
							$filtered = array_intersect_key($array, array_flip($keys));
						}
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__."() expects parameter two to be a callable function"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__."() expects paramater one to be an array"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__."() expects two parameters, an array and a callable function"
			);
		}

		return $filtered;
	}

	/**
	 * Filters an array by a key prefix
	 *
	 * I'll iterate over each key in $array. If the key starts with $prefix, 
	 * I'll add it to the result array. Array keys are preserved. 
	 *
	 * @since   0.1.0
	 * @param   array   $array   the array to filter
	 * @param   string  $prefix  the key's prefix to filter
	 * @return  array            the filtered array
	 * @throws  \BadMethodCallException    if $array or $prefix is null
	 * @throws  \InvalidArgumentException  if $array is not an array
	 * @throws  \InvalidArgumentException  if $prefix is not a string
	 */
	public static function filterByKeyPrefix($array, $prefix)
	{
		$filtered = array();
		
		// if $array and $prefix are given
		if ($array !== null && $prefix !== null) {
			// if $array is actually an array
			if (is_array($array)) {
				// if $prefix is a string
				if (is_string($prefix)) {
					// if $array is not empty
					if ( ! empty($array)) {
						// filter the array by the key's prefix
						$filtered = self::filterByKey($array, function ($k) use ($prefix) {
							return strpos($k, $prefix) === 0;
						});
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__."() expects parameter two to be a string prefix"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__."() expects parameter one to be an array"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__."() expects two parameters, an array and a string prefix"
			);
		}

		return $filtered;
	}

	/**
	 * Wildcard search for a value in an array 
	 *
	 * I'll search $haystack for $needle. Unlike PHP's native in_array() method,
	 * I'll accept begins-with (e.g., "foo*"), ends-with (e.g., "*foo"), and 
	 * contains (e.g., "*foo*") wildcard notation.
	 *
	 * For example:
	 *
	 *     Arr::in_array_wildcard('foo', ['foo', 'bar']);  // returns true
	 *     Arr::in_array_wildcard('qux', ['foo', 'bar']);  // returns false
	 *     Arr::in_array_wildcard('fo*', ['foo', 'bar']);  // returns true
	 *     Arr::in_array_wildcard('*oo', ['foo', 'bar']);  // returns true
	 *     Arr::in_array_wildcard('*o*', ['foo', 'bar']);  // returns true
	 * 
	 * @since   0.1.0
	 * @param   string    $needle    the needle to find
	 * @param   string[]  $haystack  the haystack to search
	 * @param   string    $wildcard  the wildcard character (optional; if omitted, 
	 *     defaults to '*')
	 * @return  bool                 true if the needle exists in haystack
	 * @throws  \BadMethodCallException    if $needle, $haystack, or $wildcard is null
	 * @throws  \InvalidArgumentException  if $needle is not a string
	 * @throws  \InvalidArgumentException  if $haystack is not an array
	 * @throws  \InvalidArgumentException  if $wildcard is not a string
	 */
	public static function inArray($needle, $haystack, $wildcard = '*') 
	{
		$inArray = false;

		// if $needle, $haystack, and $wildcard are given
		if ($needle !== null && $haystack !== null && $wildcard !== null) {
			// if $needle is a string
			if (is_string($needle)) {
				// if $haystack is an array
				if (is_array($haystack)) {
					// if $wildcard is a string
					if (is_string($wildcard)) {
						// if $needle contains the wildcard character
						if (strpos($needle, $wildcard) !== false) {
							// determine if the neeedle starts or ends with the wildcard
							$startsWith = \Jstewmc\PhpHelpers\Str::startsWith($haystack, $wildcard);
							$endsWith   = \Jstewmc\PhpHelpers\Str::endsWith($haystack, $wildcard);
							// set the *actual* needle
							$needle = str_ireplace($wildcard, '', $needle);
							// loop through the haystack
							foreach ($haystack as $value) {
								if ($startsWith && $endsWith) {
									$inArray = strpos($value, $needle) !== false;
								} elseif ($startsWith) {
									$inArray = \Jstewmc\PhpHelpers\Str::endsWith($value, $needle);
								} else {
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
							__METHOD__."() expects parameter three, the wildcard character, to be a string"
						);
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__."() expects parameter two, the haystack, to be an array"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__."() expects parameter one, the needle, to be a string"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__."() expects two or three parameters: needle, haystack, and wildcard"
			);
		}

		return $inArray;
	}

	/**
	 * Returns true if the array has at least one string key (excluding int strings)
	 *
	 * PHP natively treats all arrays as associative arrays. I'll consider an
	 * associative array as an array with a string key. Interally, PHP casts 
	 * string keys containing valid integers to integer type (e.g., "8" will be
	 * stored as 8).
	 *
	 * For example:
	 *
	 *    Arr::isAssoc([1, 2, 3]);                       // returns false
	 *    Arr::isAssoc(['foo', 'bar', 'baz']);           // returns false
	 *    Arr::isAssoc(['1' => 'foo', 2 => 'bar']);      // returns false (PHP casts '1' to 1)
	 *    Arr::isAssoc(['1' => 'foo', 8 => 'bar']);      // returns false (sparse doens't matter)
	 *    Arr::isAssoc(['1' => 'foo', 'bar' => 'baz']);  // returns true
	 *
	 * @since   0.1.0
	 * @param   array  $array  the array to test
	 * @return  bool           true if the array has a string key (excluding int strings)
	 */
	public static function isAssoc($array) 
	{
		$isAssoc = false;
		
		if ( ! empty($array) && is_array($array)) {
			$isAssoc = (bool) count(array_filter(array_keys($array), 'is_string'));
		}

		return $isAssoc;
	}
	
	/**
	 * Replaces all occurences of $search with $replace in $array's keys
	 *
	 * I'll return an array with all occurences of $search in the array's keys 
	 * replaced with the given $replace value (case-insensitive).
	 *
	 * @since   0.1.0
	 * @param   mixed  $search   the value being searched for (aka the needle); an 
	 *     array may be used to designate multiple neeeles
	 * @param   mixed  $replace  the replacement value that replaced found $search 
	 *     values; an array may be used to designate multiple replacements
	 * @param   array  $array    the array to replace
	 * @return  array            the array with replacements
	 * @throws  \BadMethodCallException    if $search, $replace, or $array are null
	 * @throws  \InvalidArgumentException  if $search is not a string or array
	 * @throws  \InvalidArgumentException  if $replace is not a string or array
	 * @throws  \InvalidArgumentException  if $array is not an array
	 * @see     <http://us1.php.net/str_replace>
	 */
	public static function keyStringReplace($search, $replace, $array) 
	{
		$replaced = array();
		
		// if $search, $replace, and $array are given
		if ($search !== null && $replace !== null && $array !== null) {
			// if $search is a string or an array
			if (is_string($search) || is_array($search)) {
				// if $replace is a string or an array
				if (is_string($replace) || is_array($replace)) {
					// if $array is actually an array
					if (is_array($array)) {
						// if $array isn't empty
						if ( ! empty($array)) {
							// flip the array, search/replace, and flip again
							$replaced = array_flip($array);
							$replaced = array_map(function ($v) use ($search, $replace) {
								return str_ireplace($search, $replace, $v);
							}, $replaced);
							$replaced = array_flip($replaced);
						}
					} else {
						throw new \InvalidArgumentException(
							__METHOD__."() expects the third parameter, array, to be an array"
						);
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__."() expects the second parameter, replace, to be a string or array"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__."() expects the first parameter, search, to be a string or array"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__."() expects three parameters: search, replace, and array"
			);
		}

		return $replaced;
	}

	/**
	 * Sorts an array of associative arrays by a field's value
	 *
	 * Oftentimes, you have a 0-indexed array of associative arrays. For example, 
	 * a SELECT sql query result or a display-friendly data array. I'll sort a 
	 * 0-based array of associative arrays by a field's value.
	 * 
	 * For example:
	 *
	 *   $a = [['foo' => 'c'], ['foo' => 'a'], ['foo' => 'b']];
	 *   $b = Arr::usort_field($a, 'foo');
	 *   print_r($b);  // prints [['foo' => 'a'], ['foo' => 'b'], ['foo' => 'c']]
	 * 
	 * @since   0.1.0
	 * @param   array[]  $array  the array of associative arrays to sort
	 * @param   string   $field  the associative array's field name (aka, key)
	 * @param   string   $sort   the sort order (possible values 'asc[ending]' or 
	 *     'desc[ending]) (optional; if omitted, defaults to 'asc') (case-insensitive)
	 * @return  array[]         the sorted array
	 * @throws  \BadMethodCallException    if $array, $field, or $sort is null
	 * @throws  \InvalidArgumentException  if $array is not an array
	 * @throws  \InvalidArgumentException  if $field is not a string
	 * @throws  \InvalidArgumentException  if $sort is not a string
	 * @throws  \InvalidArgumentException  if $sort is not the string 'asc[ending]' or 
	 *     'desc[ending]'
	 * @throws  \InvalidArgumentException  if $array is not an array of arrays with
	 *     the key $field
	 */
	public static function sortByField($array, $field, $sort = 'asc')
	{	
		// if $array, $field, and $sort are given
		if ($array !== null && $field !== null && $sort !== null) {
			// if $array is actually an array
			if (is_array($array)) {
				// if $field is a string
				if (is_string($field)) {
					// if $sort is a string
					if (is_string($sort)) {
						// if $sort is a valid sort
						if (in_array(strtolower($sort), array('asc', 'ascending', 'desc', 'descending'))) {
							// if $array is an array of arrays with $field key
							$passed = array_filter($array, function ($v) use ($field) {
								return is_array($v) && array_key_exists($field, $v);
							});
							if (count($array) === count($passed)) {
								// sort the array using the field's value
								// by default, usort() will return results in ascending order
								//
								usort($array, function ($a, $b) use ($field) {
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
									$array = array_reverse($array);
								}
							} else {
								throw new \InvalidArgumentException(
									__METHOD__."() expects parameter one to be an array of arrays with the key '$field'"
								);
							}
						} else {
							throw new \InvalidArgumentException(
								__METHOD__."() expects parameter three, sort, to be 'asc[ending]' or 'desc[ending]'"
							);
						}
					} else {
						throw new \InvalidArgumentException(
							__METHOD__."() expects parameter three, sort, to be a string sort order"
						);
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__."() expects parameter two, field, to be a string field name"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__."() expects parameter one, array, to be an array"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__."() expects two or three parameters"
			);
		}
		
		return $array;
	}

	/**
	 * Sorts an array of objects using a public property's value
	 *
	 * @since   0.1.0
	 * @param   object[]  $array     the array of objects to sort
	 * @param   string    $property  the object's public property name (may be a magic
	 *     public property via the object's __get() method)
	 * @param   string    $sort      the sort order (possible values 'asc[ending]' or 
	 *     'desc[ending]) (optional; if omitted, defaults to 'asc') (case-insensitive)
	 * @return  object[]             the sorted array
	 * @throws  \BadMethodCallException    if $array, $property, or $sort is null
	 * @throws  \InvalidArgumentException  if $array is not an array
	 * @throws  \InvalidArgumentException  if $property is not a string
	 * @throws  \InvalidArgumentException  if $sort is not a string
	 * @throws  \InvalidArgumentException  if $sort is not the string 'asc[ending]' or 
	 *     'desc[ending]'
	 * @throws  \InvalidArgumentException  if $array is not an array of objects with
	 *     the public property $property
	 */
	public static function sortByProperty($array, $property, $sort = 'asc') 
	{
		// if $array, $property, and $sort are given
		if ($array !== null && $property !== null && $sort !== null) {
			// if $array is actually an array
			if (is_array($array)) {
				// if $property is a string
				if (is_string($property)) {
					// if $sort is a string
					if (is_string($sort)) {
						// if $sort is a valid sort
						if (in_array(strtolower($sort), array('asc', 'ascending', 'desc', 'descending'))) {
							// if $array is an array of objects with $property
							// use property_exists() to allow null values of explicit public properties
							// use isset() to allow "magic" properties via the __get() magic method
							//
							$passed = array_filter($array, function ($v) use ($property) {
								return is_object($v) 
									&& (property_exists($v, $property) || isset($v->$property));
							});
							if (count($array) === count($passed)) {
								// sort the array using the property's value
								// by default, usort() will return results in ascending order
								//
								usort($array, function ($a, $b) use ($property) {
									if ($a->$property < $b->$property) {
										return -1;
									} elseif ($a->$property > $b->$property) {
										return 1;
									} else {
										return 0;
									}
								});
								// if the sort order is descending
								$sort = strtolower($sort);
								if ($sort === 'desc' || $sort === 'descending') {
									$array = array_reverse($array);
								}
							} else {
								throw new \InvalidArgumentException(
									__METHOD__."() expects parameter one to be an array of objects with public property '$property'"
								);
							}
						} else {
							throw new \InvalidArgumentException(
								__METHOD__."() expects parameter three, sort, to be 'asc[ending]' or 'desc[ending]'"
							);
						}
					} else {
						throw new \InvalidArgumentException(
							__METHOD__."() expects parameter three, sort, to be a string sort order"
						);
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__."() expects parameter two, property, to be a string public property name"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__."() expects parameter one, array, to be an array"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__."() expects two or three parameters"
			);
		}

		return $array;
	}

	/**
	 * Sorts an array of objects using a method's return value
	 *
	 * @since   0.1.0
	 * @param   object[]  the array of objects to sort
	 * @param   string    the name of the public method to use (may be a "magic" 
	 *     method via the object's __call() magic method)
	 * @param   string    the sort order (possible values 'asc[ending]' or 
	 *     'desc[ending]) (optional; if omitted, defaults to 'asc') (case-insensitive)  
	 * @return  object[]  the sorted array
	 * @throws  \BadMethodCallException    if $array, $property, or $sort is null
	 * @throws  \InvalidArgumentException  if $array is not an array
	 * @throws  \InvalidArgumentException  if $property is not a string
	 * @throws  \InvalidArgumentException  if $sort is not a string
	 * @throws  \InvalidArgumentException  if $sort is not the string 'asc[ending]' or 
	 *     'desc[ending]'
	 * @throws  \InvalidArgumentException  if $array is not an array of objects with
	 *     the public property $property
	 */
	public static function sortByMethod($array, $method, $sort = 'asc') 
	{
		// if $array, $method, and $sort are given
		if ($array !== null && $method !== null && $sort !== null) {
			// if $array is actually an array
			if (is_array($array)) {
				// if $method is a string
				if (is_string($method)) {
					// if $sort is a string
					if (is_string($sort)) {
						// if $sort is a valid sort
						if (in_array(strtolower($sort), array('asc', 'ascending', 'desc', 'descending'))) {
							// if $array is an array of objects with public method $method
							// use is_callable() to allow "magic" methods
							//
							$passed = array_filter($array, function ($v) use ($method) {
								return is_object($v) && is_callable(array($v, $method));
							});
							if (count($array) === count($passed)) {
								// sort the array using the property's value
								// by default, usort() will return results in ascending order
								//
								usort($array, function ($a, $b) use ($method) {
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
									$array = array_reverse($array);
								}
							} else {
								throw new \InvalidArgumentException(
									__METHOD__." expects parameter one to be an array of objects with public property '$property'"
								);
							}
						} else {
							throw new \InvalidArgumentException(
								__METHOD__." expects parameter three, sort, to be 'asc[ending]' or 'desc[ending]'"
							);
						}
					} else {
						throw new \InvalidArgumentException(
							__METHOD__." expects parameter three, sort, to be a string sort order"
						);
					}
				} else {
					throw new \InvalidArgumentException(
						__METHOD__." expects parameter two, property, to be a string public property name"
					);
				}
			} else {
				throw new \InvalidArgumentException(
					__METHOD__." expects parameter one, array, to be an array"
				);
			}
		} else {
			throw new \BadMethodCallException(
				__METHOD__." expects two or three parameters"
			);
		}

		return $array;
	}
}
