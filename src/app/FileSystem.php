<?php

namespace App;

class FileSystem
{
    /**
     * Creates a new directory
     * 
     * @param  {string} $input New directory path (relative to root)
     * @return {boolean}
     */
    public static function createDirectory($input = null, $absolutePath = false)
    {
        // ERROR: Missing input -----------------------------------------------
        if (! isset($input)) {
            return false;
        }

        // Trim right slashes
        $input  = rtrim($input, "/");

        // Assemble path
        $path = $absolutePath ? $input : APP_ROOT."/".$input;

        // ERROR: Directory already exists ------------------------------------
        if (is_dir($path)) {
            return false;
        }

        // Third param is used to create folders recursively
        mkdir($path, 0777, true);

        return true;
    }

    /**
     * Renames a directory
     * 
     * @param  {string} $old Old directory name
     * @param  {string} $new New directory name
     * @return {boolean}
     */
    public static function renameDirectory($old = null, $new = null)
    {
        // ERROR: Missing inputs ----------------------------------------------
        if (! isset($old, $new)) {
            return false;
        }

        // Assemble paths
        $old = APP_ROOT . "/" . $old;
        $new = APP_ROOT . "/" . $new;

        // Can't overwrite!
        if (is_dir($new)) {
            return false;
        }

        return rename($new, $old);
    }

    /**
     * Deletes a directory
     * 
     * @param  {string} $input Directory to delete, relative to root
     * @return {boolean}
     */
    public static function deleteDirectory($input = null)
    {
        // ERROR: Missing input -----------------------------------------------
        if (! isset($input)) {
            return false;
        }

        // Assemble path
        $path = APP_ROOT . "/" . $input;

        // ERROR: Directory doesn't exist -------------------------------------
        if (! is_dir($path)) {
            return false;
        }

        // Get all files in it, if any
        $filenames = glob($path."/*.*");

        // Delete all files in folder, if any
        if (! empty($filenames)) {
            foreach($filenames as &$filename) {
                unlink($filename);
            }
        }

        // Delete path
        return rmdir($path);
    }


    public static function deleteDirectoryRecursively($dirPath, $absolutePath = false) {
        $dirPath = $absolutePath ? $dirPath : APP_ROOT . "/" . $dirPath;
        if (! is_dir($dirPath)) {
            return false;
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDirectoryRecursively($file, true);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public static function emptyDirectory($dirPath) {
        $dirPath = APP_ROOT . "/" . $dirPath;
        if (! is_dir($dirPath)) {
            return false;
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDirectoryRecursively($file, true);
            } else {
                unlink($file);
            }
        }
        return true;
    }
}
