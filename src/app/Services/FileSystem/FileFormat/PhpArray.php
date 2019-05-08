<?php

namespace App\Services\FileSystem\FileFormat;

use App\Services\FileSystem\FileSystem;
use App\Services\FileSystem\Interfaces\FileFormatInterface;

class PhpArray implements FileFormatInterface
{
    private $filePath;
    private $fileContent;
    private $data;

    /**
     * If TRUE, stored file will be minified
     * If FALSE, stored file will be more readable (tabs, newlines, whitespace)
     *
     * @var bool
     */
    private $minify = true;

    public function __construct(string $path = null)
    {
        if ($path !== null) {
            $this->setFilePath($path);
        }
    }

    public function setMinify(bool $minify): PhpArray
    {
        $this->minify = $minify;
        return $this;
    }

    public function setFilePath(string $path): PhpArray
    {
        $this->filePath = $path;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): PhpArray
    {
        $this->data = $data;
        return $this;
    }

    public function readFile(): void
    {
        $this->data = FileSystem::loadFile($this->filePath);
    }

    public function writeFile(): void
    {
        FileSystem::saveFile($this->filePath, $this->fileContent);
    }

    /**
     * Reads file content and stores data into $this->data
     *
     * @return Env
     */
    public function readDataFromFile(): PhpArray
    {
        $this->readFile();

        return $this;
    }

    /**
     * Writes current data into $this->fileContent
     *
     * @return Env
     */
    public function writeDataToFile(): PhpArray
    {
        // Minified content
        if ($this->minify) {
            $template = "<?php return [%CONTENT%];\n";
            $lines = [];
            foreach ($this->data as $key => $value) {
                $lines[] = "'{$key}'=>'{$value}'";
            }
            $content = implode(',', $lines);
        }
        
        // Readable content
        else {
            $template = "<?php\n\nreturn [\n\n%CONTENT%\n\n];\n";
            $lines = [];
            $tab = str_repeat(' ', 4);
            foreach ($this->data as $key => $value) {
                $lines[] = "{$tab}'{$key}' => '{$value}',";
            }
            $content = implode("\n", $lines);
        }

        $this->fileContent = str_replace("%CONTENT%", $content, $template);

        return $this;
    }
}
