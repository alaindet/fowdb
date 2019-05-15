<?php

namespace App\Services\FileSystem\Interfaces;

interface FileSystemInterface
{
    static public function saveFile(string $path, string $content): void;
    static public function copyFile(string $from, string $to): void;

    static public function renderFile(
        string $path,
        object $vars,
        bool $shouldDeclareVariables = false
    ): string;

    static public function readFile(string $path): string;
    static public function loadFile(string $path); // array|string
    static public function loadJsonFile(string $path, bool $returnObject); // object/array
    static public function existsFile(string $path): bool;
    static public function renameFile(string $from, string $to): void;
    static public function deleteFile(string $path): void;
    
    static public function createDirectory(string $path, int $mode = null): void;
    static public function renameDirectory(string $from, string $to): void;
    static public function deleteDirectory(string $path): void;
}
