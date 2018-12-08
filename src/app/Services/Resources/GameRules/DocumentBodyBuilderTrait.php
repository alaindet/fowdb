<?php

namespace App\Services\Resources\GameRules;

/**
 * DocumentConverter properties used
 * 
 * protected $bodyLines; (from DocumentScannerTrait)
 * protected $css; (from DocumentBuilderTrait)
 * 
 * NOTE: object type hint doesn't work on the server yet (PHP 7.1.15)
 */
trait DocumentBodyBuilderTrait
{
    /**
     * Opens a generic new unit
     * Calls specific "opener" function based on current line level
     * 
     * Openers are named like openBodyUnitLEVEL
     * Ex.: openBodyUnit1, openBodyUnit2
     *
     * @param object $line Current Line
     * @param object $prev Previous Line
     * @return string HTML
     */
    // protected function openBodyUnit(object &$prev, object &$line): string
    protected function openBodyUnit(&$prev, &$line): string
    {
        $opener = 'openBodyUnit' . $line->level;
        return $this->$opener($prev, $line);
    }

    /**
     * Closes all units between two nesting levels in sequential steps
     * Calls specific "closer" functions for each step
     * 
     * Closers are named like closeBodyUnitLEVEL
     * Ex.: closeBodyUnit1, closeBodyUnit2
     *
     * @param object $from
     * @param object $to
     * @return string
     */
    // protected function closeBodyUnits(object &$from, object &$to): string
    protected function closeBodyUnits(&$from, &$to): string
    {
        $html = '';

        for ($level = $from->level; $level >= $to->level; $level--) {
            $closer = 'closeBodyUnit' . $level;
            $html .= $this->$closer($level, max($level-1, $to->level));
        }

        return $html;
    }

    /**
     * Opens a level 1 body unit
     *
     * @param object $prev Previous line
     * @param object $line Current line
     * @return string HTML
     */
    // private function openBodyUnit1(object &$prev, object &$line): string
    private function openBodyUnit1(&$prev, &$line): string
    {
        $dTag = $line->dotlessTag;
        $hider = 'hide-cr-'.$dTag;
        $top = $this->css['bdy.1.top'];

        return (
            '<div class="'.$this->css['bdy.1'].'">'.

                // Title
                '<div class="'.$this->css['bdy.1.ttl'].'">'.
                    '<h3>'.
                        '<a name="'.$dTag.'" href="#'.$dTag.'">'
                            .$line->tag.
                        '</a> '.
                        '<span '.
                            'class="js-hider pointer" '.
                            'data-target="#'.$hider.'"'.
                        '>'.
                            '<i class="fa fa-chevron-down"></i> '.
                            $line->content.
                        '</span> '.
                        '<a href="#toc" class="'.$top.'">&#129049;</a>'.
                    '</h3>'.
                '</div>'.

                // Body
                '<div class="'.$this->css['bdy.1.bdy'].'" id="'.$hider.'">'.
                    '<ul>'
                    // </ul>
                // </div>

            // </div>
        );
    }

    /**
     * Closes a level 1 body unit
     *
     * @param int $from Inner nesting
     * @param int $to Outer nesting
     * @return string HTML
     */
    private function closeBodyUnit1(int $from, int $to): string
    {
        return '</ul></div></div>';
    }

    /**
     * Opens a level 2 body unit
     *
     * @param object $prev Previous line
     * @param object $line Current line
     * @return string HTML
     */
    // private function openBodyUnit2(object &$prev, object &$line): string
    private function openBodyUnit2(&$prev, &$line): string
    {
        // Ex.: "1401" => 1400
        $dTag = intval($line->dotlessTag);
        $parent = intval( $dTag / 100 ) * 100;
        $class = $this->css['bdy.2.top'];

        return (
            '<li class="'.$this->css['bdy.2'].'">'.
                '<span class="'.$this->css['bdy.2.ttl'].'">'.
                    '<a href="#'.$dTag.'" name="'.$dTag.'">'.
                        $line->tag.
                    '</a>'.
                    ' '.$line->content.' '.
                    '<a href="#'.$parent.'" class="'.$class.'">&#129049;</a>'.
                '</span>'.
                '<ul>'
                // </ul>
            // </li>
        );
    }

    /**
     * Closes a level 2 body unit
     *
     * @param int $from Inner nesting
     * @param int $to Outer nesting
     * @return string HTML
     */
    private function closeBodyUnit2(int $from, int $to): string
    {
        return (
            '</ul>'.
            '</li>'.
            (
                $from === $to
                    ? '<hr class="'.$this->css['hru'].'">'
                    : ''
            )
        );
    }

    /**
     * Opens a level 3 body unit
     *
     * @param object $prev Previous line
     * @param object $line Current line
     * @return string HTML
     */
    // private function openBodyUnit3(object &$prev, object &$line): string
    private function openBodyUnit3(&$prev, &$line): string
    {
        $dTag = &$line->dotlessTag;
        return (
            '<li class="'.$this->css['bdy.3'].'">'.
                '<a href="#'.$dTag.'" name="'.$dTag.'">'.$line->tag.'</a>'.
                ' '.$line->content
            // </li>
        );
    }

    /**
     * Closes a level 3 body unit
     *
     * @param int $from Inner nesting
     * @param int $to Outer nesting
     * @return string HTML
     */
    private function closeBodyUnit3(int $from, int $to): string
    {
        return '</li>';
    }

    /**
     * Opens a level 4 body unit
     *
     * @param object $prev Previous line
     * @param object $line Current line
     * @return string HTML
     */
    // private function openBodyUnit4(object &$prev, object &$line): string
    private function openBodyUnit4(&$prev, &$line): string
    {
        $dTag = &$line->dotlessTag;
        return (
            ($line->level > $prev->level ? '<ul>' : '').
            '<li class="'.$this->css['bdy.4'].'">'.
                '<a href="#'.$dTag.'" name="'.$dTag.'">'.$line->tag.'</a>'.
                ' '.$line->content
                // ?</ul>
            // </li>
        );
    }

    /**
     * Closes a level 4 body unit
     *
     * @param int $from Inner nesting
     * @param int $to Outer nesting
     * @return string HTML
     */
    private function closeBodyUnit4(int $from, int $to): string
    {
        return (
            ($from > $to ? '</ul>' : '').
            '</li>'
        );
    }

    /**
     * Opens a level 5 body unit
     *
     * @param object $prev Previous line
     * @param object $line Current line
     * @return string HTML
     */
    // private function openBodyUnit5(object &$prev, object &$line): string
    private function openBodyUnit5(&$prev, &$line): string
    {
        $dTag = &$line->dotlessTag;
        return (
            ($line->level > $prev->level ? '<ul>' : '').
            '<li class="'.$this->css['bdy.5'].'">'.
                '<a href="#'.$dTag.'" name="'.$dTag.'">'.$line->tag.'</a>'.
                ' '.$line->content
            // </li>
        );
    }

    /**
     * Closes a level 5 body unit
     *
     * @param int $from Inner nesting
     * @param int $to Outer nesting
     * @return string HTML
     */
    private function closeBodyUnit5(int $from, int $to): string
    {
        return (
            ($from > $to ? '</ul>' : '').
            '</li>'
        );
    }
}
