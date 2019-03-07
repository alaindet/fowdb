<?php

namespace App\Clint;

use App\Clint\CommandInterface;
use App\Services\FileSystem\FileSystem;

class Commands
{
    private $list;

    public function __construct()
    {
        $this->list = FileSystem::loadFile(path_data('app/clint.php'));
    }

    public function exists(string $name): bool
    {
        return isset($this->list[$name]);
    }

    public function class(string $name): string
    {
        return $this->list[$name];
    }

    public function new(string $name): CommandInterface
    {
        $class = $this->list[$name];
        $command = new $class();
        return $command;
    }
}
