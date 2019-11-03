<?php

namespace App\Services\Resources\GameRules;

use App\Services\Resources\GameRules\DocumentScannerTrait;
use App\Services\Resources\GameRules\DocumentBuilderTrait;
use App\Services\Resources\GameRules\DocumentBodyBuilderTrait;
use App\Services\FileSystem;

/**
 * Converts a Force of Will Comprehensive Rules .txt file into .html
 * To convert the original .pdf to a processable .txt file, see
 * /.dev/_/cr-convert/README.md
 * 
 * Expected format
 * |---------------------------------------------------------------------------
 * | Title line
 * | Title line
 * | Title line
 * | Body line = [tag] [text]
 * | ...
 * | Body line
 * | EMPTY LINE
 * |---------------------------------------------------------------------------
 * 
 * Example
 * |---------------------------------------------------------------------------
 * | Force of Will Comprehensive Rules ver. 8.01
 * | Last Update: September 5th, 2018
 * | Effective: September 21th, 2018
 * | _100. Nesting level 1 (tag = _100., text = Nesting level 1)
 * | __101. Nesting level 2
 * | ___101.1 Nesting level 3
 * | ____101.1a Nesting level 4
 * | _____101.1a-i Nesting level 5
 * | ...
 * | EMPTY LINE
 * |---------------------------------------------------------------------------
 * 
 */
class DocumentConverter
{
    /**
     * DocumentScannerTrait
     * 
     * Properties (private not shown)
     * protected $titleLines;
     * protected $tocLines;
     * protected $bodyLines;
     * 
     * Methods (private not shown)
     * protected function scanDocument(): void;
     */
    use DocumentScannerTrait;

    /**
     * DocumentBuilderTrait
     * 
     * Properties (private not shown)
     * protected $css;
     * 
     * Methods (private not shown)
     * protected function buildDocument(): string;
     */
    use DocumentBuilderTrait;

    /**
     * Absolute path to input .txt file
     *
     * @var string
     */
    protected $inputFilePath;

    /**
     * Absolute path to output .html file
     *
     * @var string
     */
    protected $outputFilePath;

    /**
     * Nesting char used to calculate nesting level of line from its tag
     * Default is '_'
     *
     * @var string
     */
    protected $nestingChar;

    /**
     * Deepest nesting level possible
     * Default is 5
     *
     * @var int
     */
    protected $nestingDeepestLevel;

    /**
     * Deepest nesting level shown into the table of contents
     *
     * @var int
     */
    protected $tocDeepestLevel;

    public function __construct()
    {
        // Set defaults
        $this->nestingChar = '_';
        $this->nestingDeepestLevel = 5;
        $this->tocDeepestLevel = 1;
    }

    /**
     * Sets the absolute path to the input file
     *
     * @param string $absolutePath
     * @return self
     */
    public function setInputFilePath(string $absolutePath): self
    {
        $this->inputFilePath = $absolutePath;

        return $this;
    }

    /**
     * Sets the absolute path to the output file
     *
     * @param string $absolutePath
     * @return self
     */
    public function setOutputFilePath(string $absolutePath): self
    {
        $this->outputFilePath = $absolutePath;

        return $this;
    }

    /**
     * Sets the nesting char used to calculate the nesting level of a line
     *
     * @param string $char
     * @return self
     */
    public function setNestingCharacter(string $char): self
    {
        $this->nestingChar = $char;

        return $this;
    }

    /**
     * Sets the deepest nesting level to use
     *
     * @param int $level
     * @return self
     */
    public function setNestingDeepestLevel(int $level): self
    {
        $this->nestingDeepestLevel = $level;

        return $this;
    }

    /**
     * Sets the deepest nesting level shown into the table of contents
     *
     * @param int $level
     * @return self
     */
    public function setTocDeepestLevel(int $level): self
    {
        $this->tocDeepestLevel = $level;

        return $this;
    }

    /**
     * Converts the input .txt file into a processed .html file, then stores it
     *
     * @return void
     */
    public function convert(): void
    {
        // Populates: $this->titleLines, $this->tocLines, $this->bodyLines
        $this->scanDocument();

        // Build the output as string
        $this->output = $this->buildDocument();

        // Store the HTML output
        FileSystem::saveFile($this->outputFilePath, $this->output);
    }
}
