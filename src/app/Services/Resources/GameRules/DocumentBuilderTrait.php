<?php

namespace App\Services\Resources\GameRules;

// Temporary?
use App\Services\Resources\GameRules\DocumentBodyBuilderTrait;

/**
 * DocumentConverter properties used
 * 
 * protected $titleLines;
 * protected $tocLines;
 */
trait DocumentBuilderTrait
{
    use DocumentBodyBuilderTrait;

    /**
     * Maps HTML component names to their CSS class
     * 
     * Rules
     * - Component names are 3-letters long
     * - Abbreviations prefer consonants or first characters for readability
     *
     * @var array
     */
    protected $css = [
        'rot'       => 'fd-cr',          // Root component
        'ttl'       => 'fd-cr-ttl',      // Title component
        'toc'       => 'fd-cr-toc',      // Table of Contents (ToC)
        'toc.ttl'   => 'fd-cr-toc__ttl', // ToC Title
        'toc.bdy'   => 'fd-cr-toc__bdy', // ToC Body
        'toc.itm'   => 'fd-cr-toc__itm', // ToC Item
        'bdy'       => 'fd-cr-bdy',      // Body component
        'bdy.1'     => 'fd-cr-bdy__1',
        'bdy.1.ttl' => 'fd-cr-bdy__1__ttl',
        'bdy.1.bdy' => 'fd-cr-bdy__1__bdy',
        'bdy.2'     => 'fd-cr-bdy__2',
        'bdy.2.ttl' => 'fd-cr-bdy__2__ttl',
        'bdy.2.bdy' => 'fd-cr-bdy__2__bdy',
        'bdy.3'     => 'fd-cr-bdy__3',
        'bdy.4'     => 'fd-cr-bdy__4',
        'bdy.5'     => 'fd-cr-bdy__5',
        'hru'       => 'fd-cr-hru',      // Horizontal Rule
    ];

    /**
     * Builds the whole HTML document
     *
     * @return string
     */
    protected function buildDocument(): string
    {
        return (
            '<div class="'.$this->css['rot'].'">'.
                $this->buildTitle().
                $this->buildTableOfContents().
                $this->buildBody().
            '</div>'
        );
    }

    /**
     * Builds the comprehensive rules title HTML component
     *
     * @return string
     */
    protected function buildTitle(): string
    {
        $titleLine = array_shift($this->titleLines);

        $subtitleLines = '';
        foreach ($this->titleLines as $line) {
            $subtitleLines .= "<h2><small>{$line->content}</small></h2>";
        }

        return (
            '<div class="'.$this->css['ttl'].'">'.
                '<h1>'.$titleLine->content.'</h1>'.
                $subtitleLines.
            '</div>'
        );
    }

    /**
     * Builds the table of contents HTML component
     *
     * @return string
     */
    protected function buildTableOfContents(): string
    {
        return (
            '<div class="'.$this->css['toc'].'">'.
                $this->buildTableOfContentsTitle().
                $this->buildTableOfContentsBody().
            '</div>'
        );
    }

    /**
     * Builds the table of contents title sub-component
     *
     * @return string
     */
    private function buildTableOfContentsTitle(): string
    {
        return (
            '<h3 '.
                'class="'.$this->css['toc'].' js-hider pointer"'.
                'data-target="#hide-toc"'.
            '>'.
                '<i class="fa fa-chevron-down"></i>'.
                'Table of Contents'.
            '</h3>'
        );
    }

    /**
     * Builds the table of contents body sub-component
     *
     * @return string
     */
    private function buildTableOfContentsBody(): string
    {
        $list = '';

        foreach ($this->tocLines as $line) {
            $class = $this->css['toc.itm'];
            $list .= (
                '<li>'.
                    '<a class="'.$class.'" href="#'.$line->dotlessTag.'">'.
                        $line->tag.' '.$line->content.
                    '</a>'.
                '</li>'
            );
        }

        return (
            '<div class="'.$this->css['toc.ttl'].'" id="hide-toc">'.
                '<ul class="'.$this->css['toc.bdy'].'">'.
                    $list.
                '</ul>'.
            '</div>'
        );
    }

    /**
     * Builds the body component
     * 
     * Line types
     * 
     * | Level | Name            | Example    |
     * | ----- | --------------- | ---------- |
     * | 1     | Chapter         | 100.       |
     * | 2     | Paragraph Title | 101.       |
     * | 3     | Paragraph       | 101.1      |
     * | 4     | Subparagraph    | 101.1a     |
     * | 5     | Subsubparagraph | 101.1a-iii |
     * 
     * 
     * Rules
     * 
     * - Chapter lines CANNOT be adjacent
     * - Paragraph title lines CANNOT be adjacent
     *
     * @return string
     */
    protected function buildBody(): string
    {
        $html = '';
        $empty = (object) ['level' => 0];
        $last  = (object) ['level' => 1];
        
        for ($i = 0, $len = count($this->bodyLines); $i < $len; $i++) {

            $prev = $this->bodyLines[$i-1] ?? $empty;
            $line = $this->bodyLines[$i];
            $next = $this->bodyLines[$i+1] ?? $empty;

            // Close open sections before opening anything else
            if ($line->level - $prev->level <= 0) {
                $html .= $this->bodyCloseSections($prev, $line);
            }

            $html .= $this->bodyOpenSection($line, $prev, $next);

        }

        $html .= $this->bodyCloseSections($line, $last);

        return $html;
    }
}
