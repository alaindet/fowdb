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
        return array_unique(array_merge($arr1, $arr2));
    }
}
