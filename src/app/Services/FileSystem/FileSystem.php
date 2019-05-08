<?php

namespace App\Services\FileSystem;

use App\Services\FileSystem\Interfaces\FileSystemInterface;
use App\Services\FileSystem\Exceptions\FileSystemException;
use App\Services\FileSystem\Exceptions\FileNotFoundException;
use App\Services\FileSystem\Exceptions\DirectoryExistsException;
use App\Utils\Json;
use ErrorException;

class FileSystem implements FileSystemInterface
{
    /**
     * Stores a file to the given file path. Overwrites any existing file
     *
     * @param string $path
     * @param string $content Content to be written, as string
     * @return void
     */
    public static function saveFile(string $path, string $content): void
    {
        $saved = file_put_contents($path, $content);

        // ERROR: Could not save the file
        if ($saved === false) {
            throw new FileSystemException("Couldn't save to: {$path}");
        }
    }

    /**
     * Copies a file to another file path
     * Overwrites destination file path
     *
     * @param string $from
     * @param string $to
     * @return void
     */
    public static function copyFile(string $from, string $to): void
    {
        try {
            copy($from, $to);
        } catch (ErrorException $exception) {
            throw new FileNotFoundException($from);
        }
    }

    /**
     * Reads a file's content as a string
     *
     * @param string $path
     * @return string
     */
    public static function readFile(string $path): string
    {
        try {   
            return file_get_contents($path);
        } catch (ErrorException $exception) {
            throw new FileNotFoundException($path);
        }
    }

    /**
     * Checks if a file exists on the filesystem
     *
     * @param string $path
     * @return void
     */
    public static function existsFile(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * Wrapper for including .php files
     * Throws a manageable exception on failure
     *
     * @param string $path
     * @return mixed Depends on the file (string|array)
     */
    public static function loadFile(string $path)
    {
        try {
            return include $path;
        } catch (ErrorException $exception) {
            throw new FileNotFoundException($path);
        }
    }

    /**
     * Reads a .json file and returned it parsed as object or array
     *
     * @param string $path
     * @param bool $returnObject If FALSE, returns assoc array (default TRUE)
     * @return object|array
     */
    public static function loadJsonFile(string $path, bool $returnObject = true)
    {
        try {
            $json = file_get_contents($path);
            return ($returnObject) ? Json::toObject($json) : Json::toArray($json);
        } catch (ErrorException $exception) {
            throw new FileNotFoundException($path);
        }
    }

    /**
     * Renames a file. Overwrites destination file
     *
     * @param string $from
     * @param string $to
     * @return void
     */
    public static function renameFile(string $from, string $to): void
    {
        try {
            rename($from, $to);
        } catch(ErrorException $exception) {
            throw new FileNotFoundException($from);
        }
    }

    /**
     * Deletes a file
     *
     * @param string $path
     * @return void
     */
    public static function deleteFile(string $path): void
    {
        try {
            unlink($path);
        } catch (ErrorException $exception) {
            throw new FileNotFoundException($path);
        }
    }

    /**
     * Creates a directory recursively
     * 
     * Reference
     * http://linuxcommand.org/lc3_lts0090.php
     * https://www.siteground.com/tutorials/cpanel/file-permissions/
     *
     * @param string $path
     * @param integer $mode (Optional) Ex.: 0755
     * @return void
     */
    public static function createDirectory(string $path, int $mode = null): void
    {
        try {
            $mode = $mode ?? 0755;
            mkdir($path, $mode, $recursive = true);
        } catch (ErrorException $exception) {
            throw new DirectoryExistsException($path);
        }
    }

    /**
     * Renames a directory
     *
     * @param string $from
     * @param string $to
     * @return void
     */
    public static function renameDirectory(string $from, string $to): void
    {
        try {
            rename($from, $to);
        } catch(ErrorException $exception) {
            throw new DirectoryExistsException($path);
        }
    }

    /**
     * Deletes a directory recursively
     *
     * @param string $path
     * @return void
     */
    public static function deleteDirectory(string $path): void
    {
        try {
            $handle = opendir($path);

            // Loop on each file (readdir() returns a file at a time)
            while (false !== ( $baseName = readdir($handle)) ) {

                // Skip non-files
                if ($baseName === '.' || $baseName === '..') continue;

                $path = "{$path}/{$baseName}";

                // It's a directory
                if (is_dir($path)) self::deleteDirectory($path);
                
                // It's a file
                else unlink($path);
            }

            closedir($handle);

            // Remove directory now (must be and it is empty)
            rmdir($path);
        }

        // ERROR
        catch(ErrorException $exception) {
            throw new FileSystemException(
                "Could not delete directory at {$path}"
            );
        }
    }

}
