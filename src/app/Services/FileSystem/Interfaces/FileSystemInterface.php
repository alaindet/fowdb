<?php

namespace App\Services\FileSystem\Interfaces;

interface FileSystemInterface
{
    public static function saveFile(string $path, string $content): void;
    public static function copyFile(string $from, string $to): void;
    public static function readFile(string $path): string;
    public static function existsFile(string $path): bool;
    public static function loadFile(string $path); // array|string
    public static function renameFile(string $from, string $to): void;
    public static function deleteFile(string $path): void;
    
    public static function createDirectory(string $path, int $mode = null): void;
    // public static function readDirectory(string $path): array;
    public static function renameDirectory(string $from, string $to): void;
    public static function deleteDirectory(string $path): void;
}
