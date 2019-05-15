<?php

namespace App\Services\FileSystem;

use App\Services\FileSystem\Interfaces\FileSystemInterface;
use App\Services\FileSystem\Exceptions\FileSystemException;
use App\Services\FileSystem\Exceptions\FileNotFoundException;
use App\Services\FileSystem\Exceptions\DirectoryExistsException;
use ErrorException;
use App\Utils\Objects;
use App\Utils\Arrays;

abstract class FileSystem implements FileSystemInterface
{
    /**
     * Stores a file to the given file path. Overwrites any existing file
     *
     * @param string $path
     * @param string $content Content to be written, as string
     * @return void
     */
    static public function saveFile(string $path, string $content): void
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
    static public function copyFile(string $from, string $to): void
    {
        try {
            copy($from, $to);
        } catch (ErrorException $exception) {
            throw new FileNotFoundException($from);
        }
    }

    /**
     * Renders an input file as a PHP template and returns it as a string
     * 
     * When $shouldDeclareVariables = true, the object $vars is spread into
     * separate variables, with key as var name and value as var value
     *
     * @param string $path
     * @param object $vars Forbidden keys: [vars, path, __key, __value]
     * @param bool $shouldDeclareVariables
     * @return string
     */
    static public function renderFile(
        string $path,
        object $vars,
        bool $shouldDeclareVariables = false
    ): string
    {
        // Declare variables into the template's scope
        if ($shouldDeclareVariables) {
            return (
                function () use (&$vars, &$path) {
                    ob_start();
                    foreach ($vars as $__key => $__value) {
                        $$__key = $__value;
                    }
                    include $path;
                    return ob_get_clean();
                }
            )($vars);
        }

        // Pass the template the $vars object
        return (
            function () use (&$vars, &$path) {
                ob_start();
                include $path;
                return ob_get_clean();
            }
        )($vars);
    }

    /**
     * Reads a file's content as a string
     *
     * @param string $path
     * @return string
     */
    static public function readFile(string $path): string
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
    static public function existsFile(string $path): bool
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
    static public function loadFile(string $path)
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
    static public function loadJsonFile(string $path, bool $returnObject = true)
    {
        try {

            $json = file_get_contents($path);

            if (!$returnObject) {
                return Arrays::fromJson($json);
            }
            
            return Objects::fromJson($json);

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
    static public function renameFile(string $from, string $to): void
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
    static public function deleteFile(string $path): void
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
    static public function createDirectory(string $path, int $mode = null): void
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
    static public function renameDirectory(string $from, string $to): void
    {
        try {
            rename($from, $to);
        } catch(ErrorException $exception) {
            throw new DirectoryExistsException($to);
        }
    }

    /**
     * Deletes a directory recursively
     *
     * @param string $path
     * @return void
     */
    static public function deleteDirectory(string $path): void
    {
        try {
            $handle = opendir($path);

            // Loop on each file (readdir() returns a file at a time)
            while (false !== ( $baseName = readdir($handle)) ) {

                // Skip non-files
                if ($baseName === "." || $baseName === "..") continue;

                $path = "{$path}/{$baseName}";

                // It"s a directory
                if (is_dir($path)) self::deleteDirectory($path);
                
                // It"s a file
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
