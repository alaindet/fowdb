<?php

namespace App\Services\FileSystem\Exceptions;

class FileNotFoundException extends FileSystemException
{
    public function __construct(string $path)
    {
        $this->message = "File not found: {$path}";
    }
}
