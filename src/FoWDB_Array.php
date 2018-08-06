<?php

namespace App;

class FoWDB_Array
{
    /**
     * Performs UNION operation on two indexed arrays **NOT ASSOC**, removes duplicates
     *
     * @param array $arr1
     * @param array $arr2
     * @return array Union of two arrays, avoid duplicates
     */
    public static function union($arr1, $arr2)
    {
        // Initialize output
        $o = [];
        // Merge arrays
        $arr = array_merge($arr1, $arr2);
        // Loop on merged
        // (Duplicates just set their *single* element multiple times!)
        foreach($arr as $val) { $o[$val] = true; }
        // Return unique values
        return array_keys($o); 
    }
}
