<?php

namespace App\Utils;

use App\Utils\Json;

abstract class Arrays
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
     * @return mixed Same type as $carry
     */
    static public function reduce(
        array $array,
        callable $callback,
        $result = null
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
        return self::filter($array, function ($i) {
            return $i !== null;
        });
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
        $pointer = &$array;
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
    static public function whitelist(
        array $array,
        array $whitelist
    ): array
    {
        return array_intersect($array, $whitelist);
    }

    /**
     * Filters an array by its keys through a whitelist array
     *
     * @param array $toFilter
     * @param array $whitelist
     * @return array
     */
    static public function whitelistKeys(
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
    static public function defaults(
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

    /**
     * Ensures the passed $input is an array
     * If so, return it as it is
     * If not (a number, a string, etc.), return a 1-element array with $input
     * 
     * @param $input mixed Anything to be converted into an array
     * @return array
     */
    static public function makeArray($input): array
    {
        if (!is_array($input)) {
            $input = [$input];
        }

        return $input;
    }

    /**
     * Turns an object into an array
     * 
     * NOTE:
     * - Methods are ignored
     * - A public property is just addedas a key => value pair to final array
     * - A protected property has "*" prepended to key
     * - A private property has class name prepended to key
     * 
     * Ex.:
     * class Foo { public $pub = 1; protected $prot = 2; private $priv = 3; }
     * $a = (array) (new Foo());
     * print_r($a, true); // [ pub => 1, *prot => 2, Foopriv => 3 ]
     *
     * @param object $object
     * @return array
     */
    static public function fromObject(object $object): array
    {
        return (array) $object;
    }

    static public function toObject(
        array $array,
        bool $deepClone = true
    ): object
    {
        return Objects::fromArray($array, $deepClone);
    }

    static public function fromJson(string $json): array
    {
        return json_decode($json, $assoc = true);
    }

    static public function toJson(array $input): string
    {
        return Json::fromArray($input);
    }
}
