<?php

namespace App\Core\Services\Filesystem;

use App\Shared\Utils\Utils;

abstract class Filesystem
{
    static public function scan(string $dir): array
    {
        $files = [];

        foreach (scandir($dir) as $file) {
            if ($file !== '.' && $file !== '..') {
                $files[] = $dir . '/' . $file;
            }
        }

        return $files;
    }

    static public function getFilename(string $absolutePath): string
    {
        $lastSlash = strrpos($absolutePath, '/');
        $temp = substr($absolutePath, $lastSlash + 1);
        $lastDot = strrpos($temp, '.');
        $extLen = strlen($temp) - strlen(substr($temp, $lastDot));
        $filename = substr($temp, 0, $extLen);

        return $filename;
    }
}
