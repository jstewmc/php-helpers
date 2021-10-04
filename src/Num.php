<?php

namespace Jstewmc\PhpHelpers;

/**
 * The number (aka, "num") class
 *
 * Keep in mind, a number in PHP (and hereafter in this class documentation) is
 * considered to be a (float), (int), or numeric (string).
 */
class Num
{
    /**
     * The regex for a comma-separated number (e.g., "1,000").
     */
    public const REGEX_NUMBER_COMMA = '#^([1-9](?:\d*|(?:\d{0,2})(?:,\d{3})*)(?:\.\d*[0-9])?|0?\.\d*[0-9]|0)$#';

    /**
     * The regex for a mixed number (e.g., "1 1/2").
     */
    public const REGEX_NUMBER_MIXED = '#^((\d+)\s+)?(\d+)[/\\\](\d+)$#';

    public static $cardinals = [
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
    ];

    public static $ordinals = [
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
    ];

    public static $powers = [
        'hundred'  => 100,
        'thousand' => 1000,
        'million'  => 1000000,
        'billion'  => 1000000000
    ];

    public static $suffixes = ['th', 'st', 'nd', 'rd'];

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
     * @param  float  $a        the first value
     * @param  float  $b        the second value
     * @param  float  $epsilon  the maximum allowed difference (exclusive) (optional;
     *    if omitted defaults to 0.00001)
     *
     * @return  bool  true if the values are equal
     *
     * @throws  \InvalidArgumentException  if $epsilon is not greater than zero
     *
     * @see  http://www.php.net/manual/en/language.types.float.php  man page on float type
     */
    public static function almostEqual(float $a, float $b, float $epsilon = 0.00001): bool
    {
        if ($epsilon <= 0) {
            throw new \InvalidArgumentException("epsilon should be greater than zero");
        }

        return (abs($a - $b) < $epsilon);
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
     * @param  int|float  $number  the number to bound
     * @param  int|float  $lower   the number's lower bound (inclusive)
     * @param  int|float  $upper   the number's upper bound (inclusive)
     *
     * @return  int|float  the bounded value or false
     *
     * @throws  \InvalidArgumentException  if $lower is passed and not a number
     * @throws  \InvalidArgumentException  if $upper is passed and not a number
     * @throws  \InvalidArgumentException  if $upper is less than $lower
     */
    public static function bound($number, $lower = null, $upper = null)
    {
        if (!is_numeric($number)) {
            throw new \InvalidArgumentException("number should be a number");
        }

        $hasLower = $lower !== null;
        $hasUpper = $upper !== null;

        if ($hasLower && !is_numeric($lower)) {
            throw new \InvalidArgumentException("lower bound should be a number");
        }

        if ($hasUpper && !is_numeric($upper)) {
            throw new \InvalidArgumentException("upper bound should be a number");
        }

        if ($hasUpper && $upper <= $lower) {
            throw new \InvalidArgumentException(
                "upper bound should be greater than or equal to lower bound"
            );
        }

        if ($hasLower && $hasUpper) {
            $value = min(max($number, $lower), $upper);
        } elseif ($hasLower) {
            $value = max($number, $lower);
        } elseif ($hasUpper) {
            $value = min($number, $upper);
        } else {
            $value = $number;
        }

        return $value;
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
     * @param  int|float  $number    the number to ceil
     * @param  int|float  $multiple  the multiple to ceil to (optional; if omitted,
     *    defaults to 1 (aka, PHP's native ceil() function))
     *
     * @return  int|float  the ceiling-ed number
     *
     * @throws  \InvalidArgumentException  if $number is not a number
     * @throws  \InvalidArgumentException  if $multiple is not a positive, non-zero number
     *
     * @see  http://stackoverflow.com/a/1619284  Daren Schwneke's answer to "How to
     *    round up a number to the nearest 10?" on StackOverflow
     */
    public static function ceilTo($number, $multiple = 1)
    {
        if (!is_numeric($number)) {
            throw new \InvalidArgumentException("number should be a number");
        }

        if (!is_numeric($multiple) || $multiple <= 0) {
            throw new \InvalidArgumentException("multiple should be a positive, non-zero number");
        }

        return ceil($number / $multiple) * $multiple;
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
     * @param  int|float  $number    the number to floor
     * @param  int|float  $multiple  the multiple to floor to (optional; if omitted,
     *    defaults to 1 (aka, PHP's native floor() function))
     *
     * @return  int|float
     *
     * @throws  \InvalidArgumentException  if $number is not a number
     * @throws  \InvalidArgumentException  if $multiple is not a positive, non-zero number
     *
     * @see  http://stackoverflow.com/a/1619284  Daren Schwneke's answer to "How to
     *    round up a number to the nearest 10?" on StackOverflow
     */
    public static function floorTo($number, $multiple = 1)
    {
        if (!is_numeric($number)) {
            throw new \InvalidArgumentException("number should be a number");
        }

        if (!is_numeric($multiple) || $multiple <= 0) {
            throw new \InvalidArgumentException("multiple should be a positive, non-zero number");
        }

        return floor($number / $multiple) * $multiple;
    }

    /**
     * Alias for the isInt() method
     *
     * @see  self::isInt()
     */
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- to match PHP's native method
    public static function is_int($number)
    {
        return self::isInt($number);
    }

    /**
     * Alias for the isNumeric() method
     *
     * @see  self::isNumeric()
     */
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- to match PHP's native method
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
     * @param  int|float  $number    the number to test
     * @param  string     $datatype  the column datatype name (possible values are
     *    'tiny[int]', 'small[int]', 'medium[int]', 'int[eger]', and 'big[int]')
     *    (case-insensitive) (optional; if omitted, defaults to 'int')
     *
     * @return  bool  true if the number is a valid database id
     *
     * @throws  \InvalidArgumentException  if $datatype is invalid
     */
    public static function isId($number, string $datatype = 'int'): bool
    {
        // if $number is not a positive integer
        if (!is_numeric($number) || !self::isInt($number) || $number <= 0) {
            return false;
        }

        $datatype = strtolower($datatype);

        if ($datatype === 'tiny' || $datatype === 'tinyint') {
            $isId = ($number <= 255);
        } elseif ($datatype === 'small' || $datatype === 'smallint') {
            $isId = ($number <= 65535);
        } elseif ($datatype === 'medium' || $datatype === 'mediumint') {
            $isId = ($number <= 8388607);
        } elseif ($datatype === 'int' || $datatype === 'integer') {
            $isId = ($number <= 4294967295);
        } elseif ($datatype === 'big' || $datatype === 'bigint') {
            // cast the datatype's maximum value to a float, because the
            // integer's size may be beyond PHP's maximum integer value
            $isId = ($number <= (float)'18446744073709551615');
        } else {
            throw new \InvalidArgumentException(
                "datatype should be one of the following: 'tiny[int]', " .
                    "'small[int]', 'medium[int]', 'int[eger]', or 'big[int]'; ".
                    "{$datatype} given"
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
     * @param  int|float  $number  the number to test
     *
     * @return  bool  true if $number is an integer or integer string
     */
    public static function isInt($number): bool
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
     * @param  mixed  $number  the number to test
     *
     * @return  bool  true if $number is a number
     *
     */
    public static function isNumeric($number): bool
    {
        return is_numeric($number)
            || (is_string($number) && self::val($number) !== 0);
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
     * @param  mixed  $number  the number to test
     *
     * @return  bool  true if $number is zero
     */
    public static function isZero($number): bool
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
     * @param   int|float  $number  the number to normalize
     * @param   int|float  $max     the maximum to divide into $value (optional;
     *   if omitted, defaults to 1)
     *
     * @return  int|float  a number between 1 and 0 (inclusive)
     *
     * @throws  \InvalidArgumentException  if $number is not a number
     * @throws  \InvalidArgumentException  if $max is not a positive number
     * @throws  \InvalidArgumentException  if $number is greater than $max
     */
    public static function normalize($number, $max = 1)
    {
        if (!is_numeric($number)) {
            throw new \InvalidArgumentException("number should be a number");
        }

        if (!is_numeric($max) || $max <= 0) {
            throw new \InvalidArgumentException("max should be a positive number");
        }

        if ($number > $max) {
            throw new \InvalidArgumentException("max, {$max}, should be greater than number, {$number}");
        }

        return self::bound($number / $max, 0, 1);
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
        if (!is_numeric($number)) {
            throw new \InvalidArgumentException("number should be a number");
        }

        if (!is_numeric($multiple) || $multiple <= 0) {
            throw new \InvalidArgumentException("multiple should be a positive number");
        }

        return round($number / $multiple) * $multiple;
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
    // phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh, Generic.Metrics.NestingLevel.TooHigh
    public static function val($var)
    {
        // if the value is an easy type, short-circuit
        if (is_numeric($var)) {
            return +$var;
        } elseif (is_array($var)) {
            return min(count($var), 1);
        } elseif (is_object($var)) {
            return 1;
        } elseif (is_bool($var)) {
            return (int)$var;
        }

        // otherwise, if it's not a string by now, short-circuit
        if (!is_string($var)) {
            return false;
        }

        $var = trim($var);

        // if the number is a number with commas (e.g., "1,000")
        // else, if the number is a fraction or mixed number (e.g., "1/2")
        // else, if the number has a suffix (e.g., "1st")
        // else, if the number is the name for a number  (e.g., "one hundred")
        // otherwise, it's zero
        if (preg_match(self::REGEX_NUMBER_COMMA, $var)) {
            $value = +str_replace(',', '', $var);
        } elseif (preg_match(self::REGEX_NUMBER_MIXED, $var, $m)) {
            $value = ($m[2] === '' ? 0 : $m[2]) + $m[3] / $m[4];
        } elseif (is_numeric(substr($var, 0, 1)) && in_array(substr($var, -2), self::$suffixes)) {
            $value = substr($var, 0, -2);
        } else {
            // if the string is composed *only* of valid number names
            //
            // first, lowercase $var, strip commas, and replace "-" and " and " with spaces
            // then, explode on space, trim, and filter out empty values
            // finally, merge all the possible numeric string values together
            $words = strtolower($var);
            $words = str_ireplace(',', '', $words);
            $words = str_ireplace(['-', ' and '], ' ', $words);
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
                        ['and' => '']
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
                $sum   = 0;                // the running total
                $last  = null;             // the last part

                // loop through the parts
                foreach ($parts as $part) {
                    // if the stack isn't empty
                    if (! $stack->isEmpty()) {
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

        return $value;
    }
    // phpcs:enable
}
