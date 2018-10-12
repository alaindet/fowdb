<?php

namespace App\Utils;

class Strings
{
    /**
     * Pads a string RIGHT with a filler character to a specified length
     *
     * @param string $str
     * @param integer $length
     * @param string $filler
     * @return string
     */
    public static function padRight(
        string $str,
        int $length,
        string $filler = '0'
    ): string
    {
		if (strlen($str) >= $length) return $str;
		while (strlen($str) < $length) $str .= $char;
		return $str;
    }
    
	/**
     * Pads a string LEFT with a filler character to a specified length
     *
     * @param string $str
     * @param integer $length
     * @param string $filler
     * @return string
     */
    public static function padLeft(
        string $str,
        int $length,
        string $filler = '0'
    ): string
    {
		if (strlen($str) >= $length) return $str;
		while (strlen($str) < $length) $str = $char . $str;
		return $str;
	}
}
