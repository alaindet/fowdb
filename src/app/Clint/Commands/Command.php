<?php

namespace App\Clint\Commands;

use App\Services\FileSystem\FileSystem;
use App\Clint\Clint;

abstract class Command
{
    public $name;
    protected $values;
    protected $options;
    private $clint;
    private $title;
    private $message;

    public function __construct(Clint $clint)
    {
        $this->clint = $clint;
        $this->title = "Clint command {$this->name}";
        $this->message = "Default Clint message";
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setValues(array $values): Command
    {
        $this->values = $values;

        return $this;
    }

    public function setOptions(array $options): Command
    {
        $this->options = $options;

        return $this;
    }

    public function setTitle(string $title): Command
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setMessage(string $message): Command
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    protected function getPath(string $name, string $relativePath = null): ?string
    {
        return $this->clint->getPath($name, $relativePath);
    }

    protected function loadTemplate(string $path): string
    {
        $absolutePath = $this->getPath("templates") . "/{$path}.tpl";
        return FileSystem::readFile($absolutePath);
    }

    abstract public function run(): Command;
}
