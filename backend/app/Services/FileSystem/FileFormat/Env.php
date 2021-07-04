<?php

namespace App\Services\FileSystem\FileFormat;

use App\Services\FileSystem\FileSystem;

class Env
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
    private $data = [];

    public function __construct(string $path = null)
    {
        if ($path !== null) {
            $this->setPath($path);
        }
    }

    public function setPath(string $path): Env
    {
        $this->path = $path;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Reads file content as a raw string into self::content
     *
     * @return Env
     */
    public function read(): Env
    {
        $this->content = FileSystem::readFile($this->path);
        return $this;
    }

    public function setContent(string $content): Env
    {
        $this->content = $content;
        return $this;
    }

    public function getContent(): string
    {
        if ($this->content === null) {
            $this->read();
        }
        return $this->content;
    }

    public function setData(array $data): Env
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): array
    {
        if ($this->data === []) {
            $this->parse();
        }
        return $this->data;
    }

    /**
     * Parses file content (raw string) into structured data
     * Populates self::data
     *
     * @return Env
     */
    public function parse(): Env
    {
        if ($this->content === null) {
            $this->read();
        }

        foreach (explode("\n", $this->content) as $line) {

            $line = trim($line);

            // Skip comment lines
            if ($line === "" || $line[0] === "#") {
                continue;
            }

            $length = strlen($line);
            
            // Remove intra-line comments
            $hash = strpos($line, "#");
            if ($hash !== false) {
                $char = $line[$hash];
                while($char === " " || $char === "#") {
                    $char = $line[--$hash];
                }
                $length = $hash;
            }

            $equals = strpos($line, "=");
            $key = substr($line, 0, $equals);
            $value = substr($line, ($equals + 1), $length - $equals);
            $value = str_replace('"', '', $value);
        
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Stores newly set data as .env file
     *
     * @return Env
     */
    public function store(): Env
    {
        $this->content = $this->serialize();
        FileSystem::saveFile($this->path, $this->content);
        return $this;
    }

    /**
     * Transforms self::data into file content
     *
     * @return string File content
     */
    private function serialize(): string
    {
        $lines = [];

        // Keys must be uppercase and snake_case, no numbers (Ex.: APP_NAME)
        foreach ($this->data as $key => $value) {
            $what = ['/[\s]/', '/[^_a-zA-Z]/'];
            $with = ['_', ''];
            $_key = preg_replace($what, $with, strtoupper($key));
            $_value = (strpos($value, ' ') !== false) ? "\"{$value}\"" : $value;
            $lines[] = "{$_key}={$_value}";
        }

        return implode("\n", $lines);
    }
}
