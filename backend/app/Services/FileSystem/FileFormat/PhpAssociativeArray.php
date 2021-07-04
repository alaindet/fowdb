<?php

namespace App\Services\FileSystem\FileFormat;

use App\Services\FileSystem\FileSystem;

class PhpAssociativeArray
{
    /**
     * Absolute path of the file
     *
     * @var string
     */
    private $path;

    /**
     * Raw file content as string
     *
     * @var string
     */
    private $content;

    /**
     * Parsed data extracted from content
     *
     * @var array
     */
    private $data;

    /**
     * If TRUE, stored file is minified
     * If FALSE, stored file is more readable
     *
     * @var bool
     */
    private $shouldMinify = true;

    /**
     * Should wrap array keys with single-quotes?
     * 
     * Ex.:
     * <?php return [ 'hello' => 'world' ]; // TRUE
     * <?php return [ hello => 'world' ]; // FALSE
     *
     * @var bool
     */
    private $shouldWrapKeys = true;

    /**
     * Should wrap array values with single-quotes?
     * 
     * Ex.:
     * <?php return [ 'hello' => 'world' ]; // TRUE
     * <?php return [ 'hello' => world ]; // FALSE
     *
     * @var bool
     */
    private $shouldWrapValues = true;

    public function __construct(string $path = null)
    {
        if ($path !== null) {
            $this->setPath($path);
        }
    }

    public function setPath(string $path): PhpAssociativeArray
    {
        $this->path = $path;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setData(array $data): PhpAssociativeArray
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): array
    {
        if ($this->data === null) {
            $this->parse();
        }
        return $this->data;
    }

    public function shouldMinify($flag = true): PhpAssociativeArray
    {
        $this->shouldMinify = $flag;
        return $this;
    }

    public function shouldWrapKeys($flag = true): PhpAssociativeArray
    {
        $this->shouldWrapKeys = $flag;
        return $this;
    }

    public function shouldWrapValues($flag = true): PhpAssociativeArray
    {
        $this->shouldWrapValues = $flag;
        return $this;
    }

    public function read(): PhpAssociativeArray
    {
        $this->content = FileSystem::readFile($this->path);
        return $this;
    }

    public function store(): PhpAssociativeArray
    {
        $this->content = $this->serialize();
        FileSystem::saveFile($this->path, $this->content);
        return $this;
    }

    /**
     * Parses file to structured data into self::data
     *
     * @return PhpAssociativeArray
     */
    public function parse(): PhpAssociativeArray
    {
        $this->data = FileSystem::loadFile($this->path); // .php files only!
        return $this;
    }

    private function serialize(): string
    {
        $lines = [];
        $tab = str_repeat(" ", 4);

        foreach ($this->data as $key => $value) {
            $_key = ($this->shouldWrapKeys) ? "'{$key}'" : $key;
            $_value = ($this->shouldWrapValues) ? "'{$value}'" : $value;
            if ($this->shouldMinify) {
                $lines[] = "{$_key}=>{$_value},";
            } else {
                $lines[] = "{$tab}{$_key} => {$_value},";
            }
        }

        if ($this->shouldMinify) {
            return "<?php return [".implode("", $lines)."];\n";
        } else {
            return "<?php\n\nreturn [\n\n". implode("\n", $lines) ."\n\n];\n";
        }
    }
}
