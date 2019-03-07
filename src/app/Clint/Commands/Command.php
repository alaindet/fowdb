<?php

namespace App\Clint\Commands;

use App\Clint\CommandInterface;
use App\Services\FileSystem\FileSystem;

abstract class Command implements CommandInterface
{
    protected $message = '';
    protected $title = null;
    public $name = 'clint:command:name';

    public function run(array $options, array $arguments): void
    {
        //
    }

    public function message(array $aux = null): string
    {
        return $this->message;
    }

    public function title(array $aux = null): string
    {
        return $this->title ?? $this->name;
    }

    protected function path(string $path): string
    {
        return path_src('/app/Clint/'.$path);
    }

    protected function template(string $template): string
    {
        return FileSystem::readFile(
            path_src('/app/Clint/templates/'.$template.'.tpl')
        );
    }
}
