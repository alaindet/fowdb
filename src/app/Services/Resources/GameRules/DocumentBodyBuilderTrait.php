<?php

namespace App\Services\Resources\GameRules;

// Temporary?
use App\Services\Resources\GameRules\DocumentBodyBuilderTrait;

/**
 * DocumentConverter properties used
 * 
 * protected $bodyLines;
 * protected $css; (from DocumentBuilderTrait)
 * protected $level; (from DocumentBuilderTrait)
 */
trait DocumentBodyBuilderTrait
{
    protected function bodyOpenSection(
        object &$line,
        object &$prev,
        object &$next
    ): string
    {
        $opener = 'bodyOpenSection' . $line->level;
        return $this->$opener($line, $prev, $next);
    }

    protected function bodyCloseSections(object &$from, object &$to): string
    {
        $html = '';

        for ($level = $from->level; $level >= $to->level; $level--) {
            $closer = 'bodyCloseSection' . $level;
            $html .= $this->$closer($level, max($level-1, $to->level));
        }

        return $html;
    }

    protected function bodyOpenSection1(
        object &$line,
        object &$prev,
        object &$next
    ): string
    {
        $dTag = $line->dotlessTag;
        $hider = 'hide-cr-'.$dTag;

        return (
            '<div class="'.$this->css['bdy.1'].'">'.

                // Title
                '<div class="'.$this->css['bdy.1.ttl'].'">'.
                    '<h3>'.
                        '<a name="'.$dTag.'" href="#'.$dTag.'">'
                            .$line->tag.
                        '</a>'.
                        '&nbsp;'.
                        '<span '.
                            'class="js-hider pointer" '.
                            'data-target="#'.$hider.'"'.
                        '>'.
                            '<i class="fa fa-chevron-down"></i>'.
                            $line->content.
                        '</span>'.
                        '<a href="#top">Top</a>'.
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

    protected function bodyCloseSection1(int $from, int $to): string
    {
        return '</ul></div></div>';
    }

    protected function bodyOpenSection2(
        object &$line,
        object &$prev,
        object &$next
    ): string
    {
        // Ex.: "1401" => 1400
        $dTag = intval($line->dotlessTag);
        $parent = intval( $dTag / 100 ) * 100;

        return (
            '<li class="'.$this->css['bdy.2'].'">'.
                '<span class="'.$this->css['bdy.2.ttl'].'">'.
                    '<a href="#'.$dTag.'" name="'.$dTag.'">'.
                        $line->tag.
                    '</a>'.
                    ' '.$line->content.' '.
                    '<a href="#'.$parent.'">&uarr;</a>'.
                '</span>'.
                '<ul>'
                // </ul>
            // </li>
        );
    }

    protected function bodyCloseSection2(int $from, int $to): string
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

    protected function bodyOpenSection3(
        object &$line,
        object &$prev,
        object &$next
    ): string
    {
        $dTag = &$line->dotlessTag;
        return (
            '<li class="'.$this->css['bdy.3'].'">'.
                '<a href="#'.$dTag.'" name="'.$dTag.'">'.$line->tag.'</a>'.
                ' '.$line->content
            // </li>
        );
    }

    protected function bodyCloseSection3(int $from, int $to): string
    {
        return '</li>';
    }

    protected function bodyOpenSection4(
        object &$line,
        object &$prev,
        object &$next
    ): string
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

    protected function bodyCloseSection4(int $from, int $to): string
    {
        return (
            ($from > $to ? '</ul>' : '').
            '</li>'
        );
    }

    protected function bodyOpenSection5(
        object &$line,
        object &$prev,
        object &$next
    ): string
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

    protected function bodyCloseSection5(int $from, int $to): string
    {
        return (
            ($from > $to ? '</ul>' : '').
            '</li>'
        );
    }
}
