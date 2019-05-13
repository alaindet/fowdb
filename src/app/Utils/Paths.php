<?php

namespace App\Utils;

use App\Services\Configuration\Configuration;

abstract class Paths
{
    static public function inCacheDir(string $path = null): string
    {
        return self::prependWithDir("dir.cache", $path);
    }

    static public function inDataDir(string $path = null): string
    {
        return self::prependWithDir("dir.data", $path);
    }

    static public function inRootDir(string $path = null): string
    {
        return self::prependWithDir("dir.root", $path);
    }

    static public function inSrcDir(string $path = null): string
    {
        return self::prependWithDir("dir.src", $path);
    }

    static public function inTemplatesDir(string $path = null): string
    {
        return self::prependWithDir("dir.templates", $path);
    }

    static private function prependWithDir(
        string $cacheDirKey,
        string $path = null
    ): string
    {
        $config = Configuration::getInstance();
        $dir = $config->get($cacheDirKey);
        return isset($path) ? "{$dir}/{$path}" : $dir;
    }
}
