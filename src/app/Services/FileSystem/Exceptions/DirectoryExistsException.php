<?php

namespace App\Services\FileSystem\Exceptions;

class DirectoryExistsException extends FileSystemException
{
    public function __construct(string $path)
    {
        $this->message = "Directory already exists: {$path}";
    }
}
