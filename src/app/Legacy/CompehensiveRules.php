<?php

namespace App\Legacy;

class ComprehensiveRules
{
    /**
     * It's the file handler returened by fopen() on given filename
     *
     * @var resource
     */
    private $handle;

    /**
     * This will hold the whole HTML fragment containing the CR
     *
     * @var string
     */
    private $html;

    /**
     * Current EOL character (Windows)
     * 
     * @var string
     */
    private $eol = "\r\n";

    /**
     * Opens the file, stores the file pointer, throws an excetpion if file not found
     *
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->handle = fopen($filename, "r");
        
        if (! $this->handle) {
            throw \Exception("File not found!");
        }
    }

    /**
     * Assembles whole HTML fragment.
     * Must follow a sequence
     * 1 - Build title
     * 2 - Build table of contents
     * 3 - Build body
     *
     * @param boolean $return Returns whole content if requested
     * @return mixed TRUE usually, string with HTML content if requested
     */
    public function convertToHtml($return = false)
    {
        $this->html = "<div class='cr app-boxes'>"
                    . $this->buildTitle($this->handle)
                    . $this->buildToc($this->handle)
                    . $this->buildBody($this->handle)
                    . "</div>";

        return $return ? $this->html : true;
    }

    /**
     * Saves built HTML fragment to filesystem
     *
     * @param string $filename
     * @return boolean
     */
    public function save($filename)
    {
        return file_put_contents($filename, $this->html) ? true : false;
    }

    /**
     * Builds the title
     *
     * @param resource $f File pointer of input .txt
     * @return string
     */
    private function buildTitle(&$f)
    {
        $lines = [];
        $line = "";

        while ($line != $this->eol) {
            $line = fgets($f);
            $lines[] = $line;
        }

        $title = "<h1>" . array_shift($lines) . "</h1>";
        $subtitles = "";
        foreach ($lines as &$line) {
            $subtitles .= "<h2><small>{$line}</small></h2>";
        }

        return "<header class='cr__title app-box no-styling'>{$title}{$subtitles}</header>";
    }

    /**
     * Builds table of contents, 2/3 of the sequence
     *
     * @param resource $f
     *            File pointer of input .txt
     * @return string
     */
    private function buildToc(&$f)
    {
        return "<section class='cr__toc app-box'>"
              . $this->buildTocTitle()
              . $this->buildTocBody($f)
              . "</section>";
    }

    /**
     * Build the title of Table of Contents section
     * 
     * @return string
     */
    private function buildTocTitle()
    {
        return "<header class='app-box__title js-hider pointer' data-target='#hide-toc'>"
             . "<h3><i class='fa fa-chevron-down'></i>&nbsp;Table of Contents</h3>"
             . "</header>";
    }

    /**
     * Builds the body of Table of Contents section
     *
     * @param  resource &$f File handle for the .txt input
     * @return string
     */
    private function buildTocBody(&$f)
    {
        $list = "";
        $line = fgets($f);

        while ($line != $this->eol) {
            list($code, $content) = explode(".", $line, 2);
            if (+$code % 100 == 0) {
                $list .= "<li><a class='toc__li' href='#{$code}'><span class='toc__li__code'>{$code}.</span><span class='toc__li__content'>{$content}</span></a></li>";    
            }
            $line = fgets($f);
        }

        return "<section class='app-box__content cr__toc' id='hide-toc'>"
             . "<ul class='cr__toc__list'>{$list}</ul>"
             . "</section>";
    }

    /**
     * Builds body
     *
     * @param resource $f File pointer to .txt input file
     * @return string
     */
    private function buildBody(&$f)
    {
        $output = "";
        $line = $line = $this->getLine(fgets($f));
        $nestingLevel = $this->getNestingLevel($line->code);
        
        while ($line) {
        
            // Fetch the next line
            if ($nextLine = $this->getLine(fgets($f))) {
                $nextNestingLevel = $this->getNestingLevel($nextLine->code);
            }

            // Main section => 100.
            if ($nestingLevel == 0) {

                $code = substr($line->code, 0, -1);

                $output .= "<section class='app-box cr__section'>"
                         . "<header class='app-box__title cr__section__title'>"
                         . "<h3 class='inline'><a name='{$code}' href='#{$code}'>{$line->code}</a>&nbsp;"
                         . "<span class='js-hider pointer' data-target='#hide-section-{$code}'>"
                         . "<i class='fa fa-chevron-down'></i>&nbsp;{$line->content}"
                         . "</span>&nbsp;</h3><a href='#top'>Top</a></header>"
                         . "<div class='cr__section__content app-box__content' id='hide-section-{$code}'><ul>";
            }
        
            // Subsections => 101., 101.1., 101.1a.
            else if ($nestingLevel < 4) {
        
                // Check if the next line contains a deeper level
                if ($nextNestingLevel > $nestingLevel) {

                    // Put a section anchor on paragraph titles
                    if ($nestingLevel == 1) {
                        $sectionCode = (int)((int)(substr($line->code,0,-1))/100)*100;
                        $sectionAnchor = "<a name='{$sectionCode}' href='#{$sectionCode}'>&uarr;</a>";
                    } else {
                        $sectionAnchor = "";
                    }

                    $output .= "<li class='cr__par__{$nestingLevel}'>"
                             .   "<span class='cr__par__{$nestingLevel}__title'>"
                             .     "{$this->buildLine($line)}"
                             .     $sectionAnchor
                             .   "</span>"
                             .   "<ul>";
                } else {
                    $output .= "<li class='cr__par__{$nestingLevel}'>{$this->buildLine($line)}</li>";
                }
        
            }
        
            // Deepest subsection => 101.1a-iii.
            else {
                $output .= "<li class='cr__par__{$nestingLevel}'>{$this->buildLine($line)}</li>";
            }

            // Output some closing tags
            if ($nextNestingLevel - $nestingLevel < 0) {
                // Close list(s)
                for ($i = 0, $len = abs($nextNestingLevel - $nestingLevel); $i < $len; ++$i) {
                    $output .= "</ul></li>";
                }
                // Close section
                if ($nextNestingLevel == 0) {
                    $output .= "</ul></div></section>";
                }
                // Add <hr> to level 1 lists
                if ($nextNestingLevel == 1) {
                    $output .= "<hr>";
                }
            }

            // Swap next line with current line
            $line = $nextLine;
            $nestingLevel = $nextNestingLevel;
        }

        // Close file
        fclose($f);

        return "<section class='cr__body'>{$output}</div>";
    }

    /**
     * Converts unwanted symbols of current line 
     * 
     * @param  string $line
     * @return mixed Object with "code" and "content" props or false
     */
    private function getLine($line)
    {
        if ($line == $this->eol) {
            return false;
        }

        $temp = explode(" ", str_replace(["【", "】"], ["[", "]"], htmlentities($line)), 2);
        $code = substr($temp[0], 0, -1); // Code without last dot

        return (object) [
            'code'    => $temp[0],
            'content' => $temp[1]
        ];
    }

    private function buildLine(&$line)
    {    
        $code = substr($line->code, 0, -1);
        return "<a name='{$code}' href='#{$code}'>{$line->code}</a>&nbsp;{$line->content}";
    }

    /**
     * Gets nesting level of given code from a line
     * 0 => 100.
     * 1 => 101.
     * 2 => 101.1.
     * 3 => 101.1a.
     * 4 => 101.1a-i.
     *
     * @param string $code
     * @return int
     */
    private function getNestingLevel(&$code)
    {
        $bits = explode(".", $code);

        // Trick: if like 100 return 0, if like 105 return 1
        if (count($bits) == 2) { // If there's just the final dot (Ex.: 100. or 105.)
            return $bits[0] % 100 ? 1 : 0;
        }

        // No chars into second bit
        if (! preg_match("/[a-zA-Z]/", $bits[1])) {
            return 2;
        }

        // No hyphen into second bit
        if (! strpos($bits[1], "-")) {
            return 3;
        }

        return 4;
    }
}
