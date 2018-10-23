<?php

namespace App\Services;

use App\Exceptions\FileSystemException;
use App\Base\Base as BaseClass;

class FileSystem extends BaseClass
{
    /**
     * Loads a file and its data, must be a .php file!
     * 
     * @param string $path Absolute path with extension
     * @return any Anything contained in the file
     */
    public static function loadFile(string $path = null)
    {
        if (!isset($path)) {
            throw new FileSystemException('Path to file not provided');
        }

        if (!self::existsFile($path)) {
            throw new FileSystemException("No file exists at {$path}");
        }

        return require $path;
    }

    public static function existsFile(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * Utility function to read a file's content
     *
     * @param string $path Relative to /src/
     * @return string
     */
    public static function readFile(string $path = null): string
    {
        if (!isset($path)) {
            throw new FileSystemException('Path to file not provided');
        }

        if (!self::existsFile($path)) {
            throw new FileSystemException("No file found at path \"{$path}\"");
        }

        return file_get_contents($path);
    }

    /**
     * Utility function to save a file. Optionally preserve existing files
     *
     * @param string $path Relative to /src/
     * @param string $content The file's content to be written, as string
     * @param bool $overwrite If the file should overwrite an existing file
     * @return bool If the file was written
     */
    public static function saveFile(string $path, string $content = ''): bool
    {
        $saved = file_put_contents($path, $content);

        // ERROR: Could not save the file
        if ($saved === false) {
            throw new FileSystemException("Could not save file to: {$path}");
        }

        return $saved;
    }

    public static function renameFile(string $old, string $new): bool
    {
        if (!self::existsFile($old)) {
            throw new FileSystemException("No file found at path \"{$path}\"");
        }

        return rename($old, $new);
    }

    public static function copyFile(string $from, string $to): bool
    {
        if (!self::existsFile($from)) {
            throw new FileSystemException("No file found at path \"{$path}\"");
        }

        return copy($from, $to);
    }

    public static function deleteFile(string $path): bool
    {
        if (!self::existsFile($path)) {
            throw new FileSystemException("No file found at path \"{$path}\"");
        }

        return unlink($path);
    }
}
