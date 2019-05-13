<?php

namespace App\Clint\Core;

use App\Services\FileSystem\FileSystem;
use App\Utils\Paths;

class Template
{
    /**
     * Holds data replacing placeholders in the template, while rendering
     *
     * @var array
     */
    private $data = [];

    /**
     * Relative path to /src/app/Clint/templates
     * WITHOUT extension
     *
     * @var string
     */
    private $filePath;

    /**
     * The template file content
     *
     * @var string
     */
    private $file;

    public function setFilePath(string $path): Template
    {
        $this->filePath = Paths::inSrcDir("app/Clint/templates/{$path}.tpl");
        return $this;
    }

    public function setData(array $data): Template
    {
        $this->data = $data;
        return $this;
    }

    public function render(): Template
    {
        $this->loadTemplate($this->filePath);
        $what = array_keys($this->data);
        $with = array_values($this->data);
        $this->file = str_replace($what, $with, $this->file);
        return $this;
    }

    private function loadTemplate(): void
    {
        $this->file = FileSystem::loadFile($this->filePath);
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function saveTo(string $path): void
    {
        FileSystem::saveFile($path, $this->file);
    }
}
