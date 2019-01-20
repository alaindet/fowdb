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
    public static function union(array $a, array $b): array
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
    public static function map(
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
    public static function reduce(
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
    public static function filter(
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
    public static function filterNull(array $array): array
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
    public static function addNested(
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

    /**
     * Filters an array through a whitelist array
     *
     * @param array $array
     * @param array $whitelist
     * @return array
     */
    public static function whitelist(
        array $array,
        array $whitelist
    ): array
    {
        return array_intersect($array, $whitelist);

        // $result = [];

        // $whitelistCount = count($whitelist);
        // $arrayCount = count($array);

        // // Decide what array to loop on based on their lenghts
        // if ($arrayCount < $whitelistCount) {
        //     $count = &$arrayCount;
        //     $list = &$array;
        //     $otherList = &$whitelist;
        // } else {
        //     $count = &$whitelistCount;
        //     $list = &$whitelist;
        //     $otherList = &$array;
        // }

        // for ($i = 0; $i < $count; $i++) {
        //     $item = &$list[$i];
        //     if (in_array($item, $otherList)) $result[] = $item;
        // }

        // return $result;
    }

    /**
     * Filters an array by its keys through a whitelist array
     *
     * @param array $toFilter
     * @param array $whitelist
     * @return array
     */
    public static function whitelistKeys(
        array $toFilter,
        array $whitelist
    ): array
    {
        $results = [];

        // Whitelist is shorter (loop on that)
        if (count($whitelist) < count($toFilter)) {
            foreach ($whitelist as $allowedKey) {
                if (isset($toFilter[$allowedKey])) {
                    $results[$allowedKey] = $toFilter[$allowedKey];
                }
            }
        }
        
        // Array to be filtered is shorter (loop on that)
        else {
            foreach ($toFilter as $key => $value) {
                if (in_array($key, $whitelist)) {
                    $results[$key] = $value;
                }
            }
        }

        return $results;
    }

    /**
     * Whitelists an input array through a default array (by keys) and uses
     * default values if input is missing them
     * 
     * If a value from input is missing and its default value is NULL,
     * the value is optional and is not stored in the resulting array
     *
     * @param array $input
     * @param array $defaults
     * @return array
     */
    public static function defaults(
        array $input,
        array $defaults
    ): array
    {
        $result = [];

        foreach ($defaults as $key => $defaultValue) {
            $value = $input[$key] ?? $defaultValue;
            if (isset($value)) $result[$key] = $value;
        }

        return $result;
    }
}
