<?php

namespace App\Services\Cache;

use App\Services\FileSystem\FileSystem;
use App\Utils\Paths;

abstract class Cache
{
    /**
     * Serializes an array and stores it into the filesystem
     *
     * @param string $path
     * @param array $data
     * @return string Absolute path of cached file
     */
    static public function store(string $path, array $data): string
    {
        $compressedContent = json_encode($data);
        $absolutePath = Paths::inCacheDir($path);
        FileSystem::saveFile($absolutePath, $compressedContent);
        return $absolutePath;
    }

    /**
     * Returns an array loaded from a serialized file
     *
     * @param string $path
     * @return array
     */
    static public function load(string $path): array
    {
        $compressedContent = FileSystem::loadFile(Paths::inCacheDir($path));
        $content = json_decode($compressedContent);
        return $content;
    }
}
