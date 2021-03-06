<?php

namespace App\Utils;

class Arrays
{
    /**
     * Calculates the union of two associative arrays
     * Duplicates are removed, $b arrays' keys overwrite $a keys
     *
     * @param array $a
     * @param array $b
     * @return array
     */
    static public function union(array $a, array $b): array
    {
        return array_unique(array_merge($a, $b));
    }

    /**
     * Similar to array_map() but works on associative arrays too
     * $callback is passed (value, key, array) as arguments
     *
     * @param array $array
     * @param callable $callback
     * @param bool $preserveKeys FALSE: use new numeric keys instead of original keys
     * @return array
     */
    static public function map(
        array $array,
        callable $callback,
        bool $preserveKeys = true
    ): array
    {
        $result = [];
        
        foreach ($array as $key => &$value) {
            $result[$key] = $callback($value, $key, $array);
        }

        return $preserveKeys ? $result : array_values($result);
    }

    /**
     * Similar to array_reduce() but works on associative arrays too
     * $callback is passed (carry, value, key, array) as arguments
     * 
     * @param array $array
     * @param callable $callback
     * @param mixed $result Initial value
     * @param bool $preserveKeys FALSE: use new numeric keys instead of original keys
     * @return mixed Same type as $carry
     */
    static public function reduce(
        array $array,
        callable $callback,
        $result = null,
        bool $preserveKeys = true
    )
    {
        // Use the array's first value if no carry is passed
        if (!isset($result)) {
            $result = array_values($array)[0];
        }

        foreach ($array as $key => &$value) {
            $result = $callback($result, $value, $key, $array);
        }

        return $result;
    }

    /**
     * Similar to array_filter() but works on associative arrays too
     * $callback is passed (value, key, array) as arguments
     * 
     * @param array $array
     * @param callable $callback
     * @param bool $preserveKeys FALSE: use new numeric keys instead of original keys
     * @return array Filtered array
     */
    static public function filter(
        array $array,
        callable $callback,
        bool $preserveKeys = true
    ): array
    {
        $result = [];
        
        foreach ($array as $key => &$value) {
            if ($callback($value, $key, $array)) {
                $result[$key] = $value;
            }
        }

        return $preserveKeys ? $result : array_values($result);
    }

    /**
     * Filters out null values from the array
     *
     * @param array $array
     * @return array
     */
    static public function filterNull(array $array): array
    {
        return self::filter($array, function ($i) { return isset($i); });
    }

    /**
     * Adds value to a nested array at the specified nesting level
     * Builds nested arrays if needed based on the passed nested keys
     * 
     * Ex.:
     * $data = [];
     * arrayAdd($data, 'Hello', 'foo', 'bar', 'baz');
     * echo $data['foo']['bar']['baz']; // 'Hello'
     *
     * @param array $array
     * @param any $value String or array of strings
     * @param string ...$nestedKeys List of strings
     * @return void
     */
    static public function addNested(
        array &$array,
        $value,
        ...$nestedKeys
    ): void
    {
        $pointer =& $array;
        foreach ($nestedKeys as $key) {
            if (!isset($pointer[$key])) $pointer[$key] = [];
            $pointer =& $pointer[$key];
        }
        $pointer[] = $value;
    }
}
