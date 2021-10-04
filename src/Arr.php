<?php

namespace Jstewmc\PhpHelpers;

/**
 * The array (aka, "arr") class
 */
class Arr
{
    /**
     * Returns the diff between $from and $to arrays
     *
     * For example:
     *
     *   Arr::diff(['foo', 'bar', 'baz'], ['bar', 'qux']);
     *   // Returns the following array...
     *   // [
     *   //   ['value' => 'foo', 'mask' => -1],
     *   //   ['value' => 'bar', 'mask' => 0],
     *   //   ['value' => 'baz', 'mask' => -1],
     *   //   ['value' => 'qux', 'mask' => 1]
     *   // ]
     *
     * @param  string[]  the actual array
     * @param  string[]  the expected array
     *
     * @return  array[]  an array of arrays with keys 'value', the string value, and
     *     'mask', and integer mask where -1 means deleted, 0 means unchanged, and 1
     *     means added
     *
     * @see  http://stackoverflow.com/a/22021254/537724  Clamarius' StackOverflow
     *     answer to "Highlight the difference between two strings in PHP" (edited
     *     to be PSR-2-compliant and to return a single array of rows instead of an
     *     array of columns).
     */
    public static function diff(array $from, array $to)
    {
        $diffs = [];

        $dm = [];
        $n1 = count($from);
        $n2 = count($to);

        for ($j = -1; $j < $n2; $j++) {
            $dm[-1][$j] = 0;
        }

        for ($i = -1; $i < $n1; $i++) {
            $dm[$i][-1] = 0;
        }

        for ($i = 0; $i < $n1; $i++) {
            for ($j = 0; $j < $n2; $j++) {
                if ($from[$i] == $to[$j]) {
                    $ad = $dm[$i - 1][$j - 1];
                    $dm[$i][$j] = $ad + 1;
                } else {
                    $a1 = $dm[$i - 1][$j];
                    $a2 = $dm[$i][$j - 1];
                    $dm[$i][$j] = max($a1, $a2);
                }
            }
        }

        $i = $n1 - 1;
        $j = $n2 - 1;

        while (($i > -1) || ($j > -1)) {
            if ($j > -1) {
                if ($dm[$i][$j - 1] == $dm[$i][$j]) {
                    $diffs[] = ['value' => $to[$j], 'mask' => 1];
                    $j--;
                    continue;
                }
            }
            if ($i > -1) {
                if ($dm[$i - 1][$j] == $dm[$i][$j]) {
                    $diffs[] = ['value' => $from[$i], 'mask' => -1];
                    $i--;
                    continue;
                }
            }
            {
                $diffs[] = ['value' => $from[$i], 'mask' => 0];
                $i--;
                $j--;
            }
        }

        $diffs = array_reverse($diffs);

        return $diffs;
    }

    /**
     * Filters an array by key
     *
     * I'll iterate over each key in $array passing it to the $callback function.
     * If the callback function returns true, the current value from $array is added
     * to the result array. Array keys are preserved.
     *
     * For example:
     *
     *     $a = ['foo', 'bar', 'baz'];
     *     Arr::filterByKey($a, function ($k) {
     *         return $k > 1;
     *     });  // returns ['baz']
     *
     * @param  array     $array     the array to filter
     * @param  callback  $callback  the function to call for each key in $array
     *
     * @return array the filtered array
     *
     * @see  http://php.net/manual/en/function.array-filter.php#99073  Acid24's filter
     *    by key function on on array_filter() man page
     */
    public static function filterBykey(array $array, callable $callback)
    {
        // if $array is empty, short-circuit
        if (empty($array)) {
            return [];
        }

        // otherwise, filter the keys
        $keys = array_filter(array_keys($array), $callback);

        // if no keys match the filter, short-circuit
        if (empty($keys)) {
            return [];
        }

        return array_intersect_key($array, array_flip($keys));
        ;
    }

    /**
     * Filters an array by a key prefix
     *
     * I'll iterate over each key in $array. If the key starts with $prefix, I'll
     * add it to the result array. Array keys are preserved.
     *
     * For example:
     *     $a = ['foo' => 'bar', 'baz' => 'qux'];
     *     Arr::filterByKeyPrefix($a, 'b');  // returns ['baz']
     *
     * @param  array   $array   the array to filter
     * @param  string  $prefix  the key's prefix to filter
     *
     * @return  array  the filtered array
     */
    public static function filterByKeyPrefix(array $array, string $prefix)
    {
        return self::filterByKey($array, function ($k) use ($prefix) {
            return strpos($k, $prefix) === 0;
        });
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
     *     Arr::inArray('foo', ['foo', 'bar']);  // returns true
     *     Arr::inArray('qux', ['foo', 'bar']);  // returns false
     *     Arr::inArray('fo*', ['foo', 'bar']);  // returns true
     *     Arr::inArray('*oo', ['foo', 'bar']);  // returns true
     *     Arr::inArray('*o*', ['foo', 'bar']);  // returns true
     *
     * @param  string    $needle    the needle to find
     * @param  string[]  $haystack  the haystack to search
     * @param  string    $wildcard  the wildcard character (optional; if omitted,
     *    defaults to '*')
     *
     * @return  bool  true if the needle exists in haystack
     */
    public static function inArray(string $needle, array $haystack, string $wildcard = '*'): bool
    {
        // if $needle doesn't contain the wildcard character, short-circuit
        if (strpos($needle, $wildcard) === false) {
            return in_array($needle, $haystack);
        }

        // determine if the neeedle starts- or ends-with the wildcard
        $startsWith = Str::startsWith($needle, $wildcard);
        $endsWith   = Str::endsWith($needle, $wildcard);

        // set the *actual* needle
        $needle = str_ireplace($wildcard, '', $needle);

        foreach ($haystack as $value) {
            if ($startsWith && $endsWith) {
                $inArray = strpos($value, $needle) !== false;
            } elseif ($startsWith) {
                $inArray = Str::endsWith($value, $needle);
            } else {
                $inArray = Str::startsWith($value, $needle);
            }
            // if the needle is in the array, stop looking
            if ($inArray) {
                return true;
            }
        }

        return false;
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
     *     Arr::isAssoc([1, 2, 3]);                       // returns false
     *     Arr::isAssoc(['foo', 'bar', 'baz']);           // returns false
     *     Arr::isAssoc(['1' => 'foo', 2 => 'bar']);      // returns false (PHP casts '1' to 1)
     *     Arr::isAssoc(['1' => 'foo', 8 => 'bar']);      // returns false (sparse doens't matter)
     *     Arr::isAssoc(['1' => 'foo', 'bar' => 'baz']);  // returns true
     *
     * @param  mixed  $array  the array to test
     *
     * @return  bool  true if the array has a string key (excluding int strings)
     */
    public static function isAssoc($array): bool
    {
        if (!is_array($array)) {
            return false;
        }

        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }

    /**
     * Returns true if $key does not exist in $array or $array[$key] is empty
     *
     * PHP's isset() method is will return false if the key does not exist or if the
     * key exists and its value is null. However, it will return true if the key
     * exists and its value is not null (including other "empty" values like '', false
     * and []).
     *
     * PHP's empty() method (or some frameworks) will throw a warning if you attempt
     * to test a non-existant key in an array.
     *
     * I, on the other hand, will return false if the key does not exist in the array
     * or if the key's value is empty.
     *
     * For example:
     *
     *     $a = ['foo' => null, 'bar' => [], 'qux' => 'hello'];
     *
     *     // when key doesn't exist (!)
     *     isset($a['quux']);           // returns false
     *     ! empty($a['quux']);         // throws key-does-not-exist warning
     *     ! Arr::isEmpty('quux', $a);  // returns false
     *
     *     // when key does exist, but value is null
     *     isset($a['foo']);           // returns false
     *     ! empty($a['foo']);         // returns false
     *     ! Arr::isEmpty('foo', $a);  // returns false
     *
     *     // when key does exist, but value is "empty" (!)
     *     isset($a['bar']);           // returns true
     *     ! empty($a['bar']);         // returns false
     *     ! Arr::isEmpty('bar', $a);  // returns false
     *
     *     // when key does exist, but value is not "empty"
     *     isset($a['qux']);           // returns true
     *     ! empty($a['qux']);         // returns true
     *     ! Arr::isEmpty('qux', $a);  // returns true
     *
     * @param  string  $key          the key's name
     * @param  array   $array        the array to test
     * @param  bool    $isZeroEmpty  a flag indicating whether or not zero is
     *    considered empty (optional; if omitted, defaults to true - i.e., the
     *    default behavior of PHP's empty() function )
     *
     * @return  bool  true if the key exists and its value is not empty
     */
    public static function isEmpty(string $key, array $array, bool $isZeroEmpty = true): bool
    {
        $isEmpty = true;

        if (array_key_exists($key, $array)) {
            $isEmpty = empty($array[$key]);
            // if the value is "empty" but zero is not considered empty
            if ($isEmpty && ! $isZeroEmpty) {
                // if the value is zero it is not empty
                $isEmpty = !Num::isZero($array[$key]);
            }
        }

        return $isEmpty;
    }

    /**
     * Replaces all occurences of $search with $replace in $array's keys
     *
     * I'll return an array with all occurences of $search in the array's keys
     * replaced with the given $replace value (case-insensitive).
     *
     * @param  string|array  $search   the value being searched for (aka the needle); an
     *    array may be used to designate multiple neeeles
     * @param  string|array  $replace  the replacement value that replaced found $search
     *    values; an array may be used to designate multiple replacements
     * @param   array  $array    the array to replace
     *
     * @return  array  the array with replacements
     *
     * @see     http://us1.php.net/str_replace  str_replace() man page
     */
    public static function keyStringReplace($search, $replace, array $array): array
    {
        if (!(is_string($search) || is_array($search))) {
            throw new \InvalidArgumentException("search should be string or array");
        }

        if (!(is_string($replace) || is_array($replace))) {
            throw new \InvalidArgumentException("replace should be a string or array");
        }

        if (empty($array)) {
            return [];
        }

        // flip the array, replace the strings, and flip it back
        $replaced = array_flip($array);

        $replaced = array_map(function ($v) use ($search, $replace) {
            return str_ireplace($search, $replace, $v);
        }, $replaced);

        return array_flip($replaced);
    }

    /**
     * Returns an array of this array's permutations (i.e., all the different
     * ways the elements can be ordered). BE CAREFUL! Permutations grow with the
     * factorial (i.e., 2 is 2, 3 is 6, 4 is 24, etc).
     *
     * For example:
     *
     *     $array = ['foo', 'bar', 'baz'];
     *
     *     $expected = [
     *         ['foo', 'bar', 'baz'],
     *         ['baz', 'foo', 'bar'],
     *         ['bar', 'foo', 'baz'],
     *         ['foo', 'baz', 'bar'],
     *         ['bar', 'baz', 'foo'],
     *         ['baz', 'bar', 'foo']
     *     ];
     *
     * @param  string[]  $array  an array of strings
     * @return  string[]  an array of $array's permutations
     * @see  http://docstore.mik.ua/orelly/webprog/pcook/ch04_26.htm  an example from
     *     O'Reilly's PHPCookbook
     */
    public static function permute(array $set): array
    {
        $perms = [];

        $j    = 0;
        $size = count($set) - 1;
        $perm = range(0, $size);

        do {
            foreach ($perm as $i) {
                $perms[$j][] = $set[$i];
            }
        } while ($perm = self::getNextPermutation($perm, $size) and ++$j);

        return $perms;
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
     *     $a = [['a' => 3], ['a' => 1], ['a' => 2]];
     *     Arr::usort_field($a, 'a'); // returns [['a' => 1], ['a' => 2], ['a' => 3]]
     *
     * @param  array[]  $array  the array of associative arrays to sort
     * @param  string   $field  the associative array's field name (aka, key)
     * @param  string   $sort   the sort order (possible values 'asc[ending]' or
     *    'desc[ending]) (optional; if omitted, defaults to 'asc') (case-insensitive)
     *
     * @return  array[]  the sorted array
     *
     * @throws  \InvalidArgumentException  if $sort is not the string 'asc[ending]' or
     *    'desc[ending]'
     * @throws  \InvalidArgumentException  if $array is not an array of arrays with
     *    the key $field
     */
    public static function sortByField(array $array, string $field, string $sort = 'asc'): array
    {
        // if $sort is invalid, short-circuit
        if (!in_array(strtolower($sort), ['asc', 'ascending', 'desc', 'descending'])) {
            throw new \InvalidArgumentException(
                "sort should be 'asc[ending]' or 'desc[ending]'"
            );
        }

        // if $array is not an array of arrays with $field key, short-circuit
        $passed = array_filter($array, function ($v) use ($field) {
            return is_array($v) && array_key_exists($field, $v);
        });
        if (count($array) !== count($passed)) {
            throw new \InvalidArgumentException(
                "array should be an array of arrays all with the key '$field'"
            );
        }

        // sort the array using the field's value (ascending, by default)
        usort($array, function ($a, $b) use ($field) {
            if ($a[$field] < $b[$field]) {
                return -1;
            } elseif ($a[$field] > $b[$field]) {
                return 1;
            } else {
                return 0;
            }
        });

        // if the sort order is descending, reverse the array
        $sort = strtolower($sort);
        if ($sort === 'desc' || $sort === 'descending') {
            $array = array_reverse($array);
        }

        return $array;
    }

    /**
     * Sorts an array of objects using a public property's value
     *
     * @param  object[]  $array     the array of objects to sort
     * @param  string    $property  the object's public property name (may be a magic
     *    public property via the object's __get() method)
     * @param  string    $sort      the sort order (possible values 'asc[ending]' or
     *    'desc[ending]) (optional; if omitted, defaults to 'asc') (case-insensitive)
     *
     * @return  object[]  the sorted array
     *
     * @throws  \InvalidArgumentException  if $sort is not the string 'asc[ending]' or
     *    'desc[ending]'
     * @throws  \InvalidArgumentException  if $array is not an array of objects with
     *    the public property $property
     */
    public static function sortByProperty(array $array, string $property, string $sort = 'asc'): array
    {
        // if $sort is invalid, short-circuit
        if (!in_array(strtolower($sort), ['asc', 'ascending', 'desc', 'descending'])) {
            throw new \InvalidArgumentException(
                "sort should be 'asc[ending]' or 'desc[ending]'"
            );
        }

        // if $array is an array of objects with $property
        // use property_exists() to allow null values of explicit public properties
        // use isset() to allow "magic" properties via the __get() magic method
        $passed = array_filter($array, function ($v) use ($property) {
            return is_object($v)
                && (property_exists($v, $property) || isset($v->$property));
        });
        if (count($array) !== count($passed)) {
            throw new \InvalidArgumentException(
                "array should be an array of objects with public property '$property'"
            );
        }

        // sort the array using the property's value (ascending, by default)
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

        return $array;
    }

    /**
     * Sorts an array of objects using a method's return value
     *
     * @param   object[]  $array   the array of objects to sort
     * @param   string    $method  the name of the public method to use (may be a
     *    "magic" method via the object's __call() magic method)
     * @param   string    $sort    the sort order (possible values 'asc[ending]' or
     *    'desc[ending]) (optional; if omitted, defaults to 'asc') (case-insensitive)
     *
     * @return  object[]  the sorted array
     *
     * @throws  \InvalidArgumentException  if $sort is not the string 'asc[ending]' or
     *     'desc[ending]'
     * @throws  \InvalidArgumentException  if $array is not an array of objects with
     *     the public method $method
     */
    public static function sortByMethod(array $array, string $method, string $sort = 'asc')
    {
        // if $sort is invalid, short-circuit
        if (!in_array(strtolower($sort), ['asc', 'ascending', 'desc', 'descending'])) {
            throw new \InvalidArgumentException(
                "sort should be 'asc[ending]' or 'desc[ending]'"
            );
        }

        // if $array is an array of objects with public method $method
        // use is_callable() to allow "magic" methods
        $passed = array_filter($array, function ($v) use ($method) {
            return is_object($v) && is_callable([$v, $method]);
        });
        if (count($array) !== count($passed)) {
            throw new \InvalidArgumentException(
                "array should be an array of objects with public method '$method'"
            );
        }

        // sort the array using the method's value (ascending, by default)
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

        return $array;
    }


    /* !Protected methods */

    /**
     * Returns the next permutation
     *
     * @return  array|false
     * @see  self:permute()
     */
    private static function getNextPermutation(array $p, int $size)
    {
        // slide down the array looking for where we're smaller than the next guy
        for ($i = $size - 1; $i >= 0 && $p[$i] >= $p[$i+1]; --$i) {
        }

        // if this doesn't occur, we've finished our permutations
        // the array is reversed: (1, 2, 3, 4) => (4, 3, 2, 1)
        if ($i == -1) {
            return false;
        }

        // slide down the array looking for a bigger number than what we found before
        for ($j = $size; $j >= 0 && $p[$j] <= $p[$i]; --$j) {
        }

        // swap them
        $tmp = $p[$i];
        $p[$i] = $p[$j];
        $p[$j] = $tmp;

        // now reverse the elements in between by swapping the ends
        for (++$i, $j = $size; $i < $j; ++$i, --$j) {
             $tmp = $p[$i];
            $p[$i] = $p[$j];
            $p[$j] = $tmp;
        }

        return $p;
    }
}
