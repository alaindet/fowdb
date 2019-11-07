<?php

namespace App\Utils;

class Time
{
    static public function date(): string
    {
        return date('Y-m-d');
    }

    static public function timestamp(string $format = 'default'): string
    {
        $formats = [
            'default' => 'Y-m-d H:i:s',
            'file' => 'Ymd_His',
            'file-nospace' => 'YmdHis',
        ];

        return date($formats[$format]);
    }

    /**
     * Accepts a "cache timestamp" like 20181212-1
     * Returns a subsequent timestamp like 20181212-2 or 20181213-1
     * 
     * If the date part (left) is today, increment the counter part (right)
     *
     * @param string $timestamp Format: 20181212-5
     * @return string A subsequent timestamp
     */
    static public function nextCacheTimestamp(string $timestamp): string
    {
        [$date, $counter] = explode("-", $timestamp);
        $today = date("Ymd"); // Ex.: 20191106

        // Bump date
        if ($date !== $today) {
            return "{$today}-1";
        }

        // Bump counter
        $counter++;
        return "{$date}-{$counter}";
    }
}
