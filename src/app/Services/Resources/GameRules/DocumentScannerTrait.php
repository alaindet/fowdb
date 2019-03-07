<?php

namespace App\Services\Resources\GameRules;

use App\Services\FileSystem\Exceptions\FileNotFoundException;

/**
 * DocumentConverter properties used
 * 
 * protected $inputFilePath
 * protected $nestingChar;
 * protected $nestingDeepestLevel;
 */
trait DocumentScannerTrait
{
    /**
     * The file handle to scan the document
     *
     * @var resource
     */
    private $handle;

    /**
     * Title lines bucket
     * Scanned title lines are stored here before building
     *
     * @var array
     */
    protected $titleLines = [];

    /**
     * Table of Contents lines bucket
     * Scanned table of contents lines are stored here before building
     *
     * @var array
     */
    protected $tocLines = [];

    /**
     * Body lines bucket
     * Scanned body lines are stored here before building
     *
     * @var array
     */
    protected $bodyLines = [];

    /**
     * Scans the entire document line by line and sorts line into their buckets
     *
     * @return void
     */
    protected function scanDocument(): void
    {
        // Open the file
        $this->handle = fopen($this->inputFilePath, 'r');

        // ERROR: File not found
        if (!$this->handle) {
            throw new FileNotFoundException($this->inputFilePath);
        }

        // Read lines
        while (!feof($this->handle)) {
            $line = fgets($this->handle);
            if ($line !== false) $this->scanLine($line);
        }

        // Close the file
        fclose($this->handle);
    }

    /**
     * Analyses a single line and decides its type (title line or body line)
     *
     * @param string $lineString
     * @return void
     */
    private function scanLine(string &$lineString): void
    {
        // Level 0 lines
        if ($lineString[0] !== $this->nestingChar) {
            $this->scanTitleLine($lineString);
        } 
        
        // Level 1+ lines
        else {
            $this->scanBodyLine($lineString);
        }
    }

    /**
     * Parses a title line as object and throws it into the title lines bucket
     *
     * @param string $lineString
     * @return void
     */
    private function scanTitleLine(string $lineString): void
    {
        $this->titleLines[] = (object) [
            'level' => 0,
            'content' => $lineString
        ];
    }

    /**
     * Parses a body line as object and throws it into the body lines bucket
     * Throws a copy into the table of contents lines bucket as well
     *
     * @param string $lineString
     * @return void
     */
    private function scanBodyLine(string $lineString): void
    {
        // Separate tag from content
        [$tag, $content] = explode(' ', $lineString, 2);

        $level = $this->getNestingLevel($tag);
        $tag = ltrim($tag, $this->nestingChar);
        $dotlessTag = rtrim($tag, '.');

        // Build the line object
        $line = (object) [
            'level' => $level,
            'tag' => $tag,
            'dotlessTag' => $dotlessTag,
            'content' => $content,
        ];

        // Add line object to table of contents bucket
        if ($line->level <= $this->tocDeepestLevel) $this->tocLines[] = $line;

        // Add line object to body bucket
        $this->bodyLines[] = $line;
    }

    /**
     * Returns nesting level, based on number of nesting characters at the
     * beginning of the input tag. Nesting character is _ by default
     * 
     * Examples
     * 
     * | Input Tag      | Level |
     * | -------------- | ----- |
     * | _100.          | 1     |
     * | __101.         | 2     |
     * | ___101.1       | 3     |
     * | ____101.1a     | 4     |
     * | _____101.1a-ii | 5     |
     *
     * @param string $tag
     * @return int
     */
    private function getNestingLevel(string &$tag): int
    {
        for ($level = $this->nestingDeepestLevel; $level >= 0; $level--) {
            $char = $tag[$level-1] ?? '';
            if ($char === $this->nestingChar) return $level;
        }
    }
}
