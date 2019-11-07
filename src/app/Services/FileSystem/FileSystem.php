<?php

namespace App\Services\FileSystem;

use App\Exceptions\FileSystemException;
use App\Base\Base as BaseClass;
use ErrorException;

/**
 * Loosely follows this convention
 * https://stackoverflow.com/a/2235762/5653974
 */
class FileSystem extends BaseClass
{
    /**
     * Loads a file and its data, must be a .php file!
     * 
     * @param string $fullPath
     * @return mixed Anything contained in the file
     */
    public static function loadFile(string $fullPath)
    {
        try {
            return include $fullPath;
        } catch (ErrorException $exception) {
            throw new FileSystemException("No file exists at {$fullPath}");
        }
    }

    /**
     * Checks if a file exists on the filesystem
     *
     * @param string $path
     * @return boolean
     */
    public static function existsFile(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * Utility function to read a file's content
     *
     * @param string $fullPath
     * @return string The file content
     */
    public static function readFile(string $fullPath): string
    {
        try {   
            return file_get_contents($fullPath);
        } catch (ErrorException $exception) {
            throw new FileSystemException("No file exists at {$fullPath}");
        }
    }

    /**
     * Stores a new file to the filesystem. Overwrites existing files if needed
     *
     * @param string $fullPath Relative to /src/
     * @param string $fileContent The file's content to be written, as string
     * @return void
     */
    public static function saveFile(
        string $fullPath,
        string $fileContent = ''
    ): void
    {
        $saved = file_put_contents($fullPath, $fileContent);

        // ERROR: Could not save the file
        if ($saved === false) {
            throw new FileSystemException(
                "Could not save file to: {$fullPath}"
            );
        }
    }

    /**
     * Renames a file
     *
     * @param string $fromFullPath Full path
     * @param string $toFullPath Full path
     * @return void
     */
    public static function renameFile(
        string $fromFullPath,
        string $toFullPath
    ): void
    {
        try {
            rename($fromFullPath, $toFullPath);
        } catch(ErrorException $exception) {
            throw new FileSystemException(
                "File already exists at {$toFullPath}"
            );
        }
    }

    /**
     * Copies a file to another destination
     * Overwrites destination path, if needed
     *
     * @param string $fromFullPath
     * @param string $toFullPath
     * @return void
     */
    public static function copyFile(
        string $fromFullPath,
        string $toFullPath
    ): void
    {
        try {
            copy($fromFullPath, $toFullPath);
        } catch (ErrorException $exception) {
            throw new FileSystemException("No file exists at {$fromFullPath}");
        }
    }

    /**
     * Deletes a file
     *
     * @param string $fullPath
     * @return void
     */
    public static function deleteFile(string $fullPath): void
    {
        try {
            unlink($fullPath);
        } catch (ErrorException $exception) {
            throw new FileSystemException("No file exists at {$fullPath}");
        }
    }

    /**
     * Creates a directory recursively
     * 
     * Reference
     * http://linuxcommand.org/lc3_lts0090.php
     * https://www.siteground.com/tutorials/cpanel/file-permissions/
     *
     * @param string $fullPath
     * @param integer $mode (Optional) Ex.: 0755
     * @return void
     */
    public static function createDirectory(
        string $fullPath,
        int $mode = null
    ): void
    {
        try {
            $mode = $mode ?? 0755;
            mkdir($fullPath, $mode = 0755, $recursive = true);
        } catch (ErrorException $exception) {
            throw new FileSystemException(
                "Directory already exists at {$fullPath}"
            );
        }
    }

    /**
     * Renames a directory
     *
     * @param string $fromFullPath
     * @param string $toFullPath
     * @return void
     */
    public static function renameDirectory(
        string $fromFullPath,
        string $toFullPath
    ): void
    {
        try {
            rename($fromFullPath, $toFullPath);
        } catch(\App\Exceptions\ErrorException $exception) {
            throw new FileSystemException(
                "Directory already exists at {$dirFullPath}"
            );
        }
    }

    /**
     * Deletes a directory recursively
     * 
     * TO DO: Rewrite it with SPL classes
     *
     * @param string $dirFullPath
     * @return void
     */
    public static function deleteDirectory(string $dirFullPath): void
    {
        try {
            $handle = opendir($dirFullPath);

            // Loop on each file (readdir() returns a file at a time)
            while (false !== ( $baseName = readdir($handle)) ) {

                // Skip non-files
                if ($baseName === '.' || $baseName === '..') continue;

                $fullPath = "{$dirFullPath}/{$baseName}";

                // It's a directory
                if (is_dir($fullPath)) self::deleteDirectory($fullPath);
                
                // It's a file
                else unlink($fullPath);
            }

            closedir($handle);

            // Remove directory now (must be and it is empty)
            rmdir($dirFullPath);
        }

        // ERROR
        catch(ErrorException $exception) {
            echo $exception->getMessage();
            die();
            // throw new FileSystemException(
            //     "Could not delete directory at {$dirFullPath}"
            // );
        }
    }
}
