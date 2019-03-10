<?php

namespace App\Services\FileSystem\FileFormats;

use App\Services\FileSystem\FileSystem;
use App\Utils\Strings;

class Env
{
    private $filePath;
    private $fileContent;
    private $data;

    public function __construct(string $path = null)
    {
        if ($path !== null) {
            $this->setFilePath($path);
        }
    }

    public function setFilePath(string $path): Env
    {
        $this->filePath = $path;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): Env
    {
        $this->data = $data;
        return $this;
    }

    public function readFile(): void
    {
        $this->fileContent = FileSystem::readFile($this->filePath);
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
    public function readDataFromFile(): Env
    {
        if (!isset($this->fileContent)) {
            $this->readFile();
        }

        foreach (explode("\n", $this->fileContent) as $line) {

            // Skip empty lines and comments
            if ($line === '' || $line[0] === '#') continue;

            // Read the line length
            $length = strlen($line);

            // Check for intra-line comments
            if (false !== $hash = strpos($line, '#')) {

                // Strip any whitespace left to the comment
                while ($line[$hash] === ' ' || $line[$hash] === '#') $hash--;

                // Update line length
                $length = $hash;
            }

            // Find position of first equals symbol
            $equals = strpos($line, '=');

            // Pull everything to the left of the first equals
            $key = substr($line, 0, $equals);

            // Pull everything to the right from the equals to end of the line
            $value = substr($line, ($equals + 1), $length - $equals);

            // Erase quotes if present in $value
            $value = str_replace('"', '', $value);

            // Store variable
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Writes current data into $this->fileContent
     *
     * @return Env
     */
    public function writeDataToFile(): Env
    {
        // Keys must be uppercase, snake _case, no numbers (Ex.: APP_NAME)
        foreach ($this->data as $key => $value) {
            $what = ['/[\s]/', '/[^_a-zA-Z]/'];
            $with = ['_', ''];
            $_key = preg_replace($what, $with, strtoupper($key));
            $_value = (strpos($value, ' ') !== false) ? "\"{$value}\"" : $value;
            $lines[] = "{$_key}={$_value}";
        }

        $this->fileContent = implode("\n", $lines);

        return $this;
    }
}
