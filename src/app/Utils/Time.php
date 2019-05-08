<?php

namespace App\Utils;

abstract class Time
{
    public static function date(): string
    {
        return date("Y-m-d");
    }

    public static function timestamp(string $format = "default"): string
    {
        $formats = [
            "default" => "Y-m-d H:i:s",
            "file" => "Ymd_His",
            "file-nospace" => "YmdHis",
            "cache" => "Ymd",
        ];

        if ($format === "cache") {
            return date($formats[$format]) . "-1";    
        }

        return date($formats[$format]);
    }

    /**
     * Accepts a "cache timestamp" like 20181212-1
     * Returns a subsequent timestamp like 20181212-2 or 20181213-1
     *
     * @param string $timestamp Format: 20181212-5
     * @return string A subsequent timestamp
     */
    public static function nextCacheTimestamp(string $timestamp): string
    {
        [$date, $index] = explode("-", $timestamp);
        $today = date("Ymd");

        // Bump date
        if ($date !== $today) return "{$today}-1";

        // Bump index
        $index++;
        return "{$date}-{$index}";
    }
}
