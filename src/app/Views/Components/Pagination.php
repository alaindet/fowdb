<?php

namespace App\Views\Components;

use App\Views\Component;
use App\Exceptions\ViewComponentException;

/**
 * Template file
 * /src/resources/views/components/pagination-with-progress.tpl.php
 * 
 * State variables
 * Array
 * (
 *     // Mandatory
 *     [pagination] => Array
 *     (
 *         [total] => 3128
 *         [current-page] => 100
 *         [last-page] => 126
 *         [more] => 1
 *         [lower-bound] => 2476
 *         [upper-bound] => 2500
 *         [link] => https://www.fowdb.altervista.org/cards
 *         [has-pagination] => 1
 *         [per-page] => 25
 *     ),
 * 
 *     // Optional
 *     [has-label] => true
 *     [css] => Array
 *     (
 *         [0] => --whatever
 *     )
 * )
 */
class Pagination extends Component
{
    public $filename = 'pagination';

    public function render(): string
    {
        $page = &$this->state['pagination']['current-page'];
        $last = &$this->state['pagination']['last-page'];
        $noLabel = &$this->state['no-label'];

        $pageNumbers = $this->buildPageNumbers($page, $last, $diameter = 5);
        $links = $this->buildLinks($pageNumbers);

        return $this->renderTemplate([
            'css' => $this->state['css'] ?? null,
            'links' => $links,
            'progress_label' => $noLabel ? '' : $this->buildLabel(),
            'progress_percentage' => number_format(100*$page/$last, 0),
        ]);
    }

    /**
     * Returns page numbers to show
     * 
     * Calculates page numbers based on current page, last page and how many
     * page links you want to show (the "diameter")
     * 
     * Returned array can have false values on second and second-to-last
     * elements to represent missing pages
     * 
     * Ex.:
     * 100 total pages
     * current page is 8
     * diameter is 3 (so 3 items shown, including the current page)
     * $pages = [1,false,7,8,9,false,100];
     *
     * @param integer $current Current page number
     * @param integer $last Total number of pages
     * @param integer $diameter How many items to show (with current, no ends)
     * @return array
     */
    private function buildPageNumbers(
        int $current,
        int $last,
        int $diameter
    ): array
    {
        $pages = [];
        $first = 1;

        // Pagination "radius" (integer casting because floor() returns double)
        $radius = (int) floor($diameter / 2);

        // Lower end (not including first page)
        [$lower, $lowerMissing] = [$current - $radius, true];
        if ($lower <= $first) {
            [$lower, $lowerMissing] = [$first + 1, false];
        }

        // Upper end (not inclusing last page)
        [$upper, $upperMissing] = [$current + $radius, true];
        if ($upper >= $last) {
            [$upper, $upperMissing] = [$last - 1, false];
        }
        
        // Build "window" without end members
        for ($i = $lower; $i <= $upper; $i++) $pages[] = $i;

        // Omissis left
        if ($lowerMissing && $pages[0] > $first + 1) {
            $pages = array_merge([false], $pages);
        }

        // Omissis right
        if ($upperMissing && $pages[count($pages)-1] < $last - 1) {
            $pages = array_merge($pages, [false]);
        }

        return $pages;
    }

    private function buildLinks(array $pageNumbers): array
    {
        $links = [];
        $first = 1;
        $current =& $this->state['pagination']['current-page'];
        $last =& $this->state['pagination']['last-page'];
        $link =& $this->state['pagination']['link'];
        $baseLink = $link . ((strpos($link,'?')===false)?'?':'&') . 'page=';
        $pageNumbersCount = count($pageNumbers);

        // Previous page
        $prev = max($current - 1, $first);
        $result[] = [
            'class' => 'prev',
            'disable' => $current === $prev,
            'link' => $baseLink . $prev,
            'page' => $prev
        ];

        // First page
        $result[] = [
            'class' => 'first' . ($current === $first ? ' current' : ''),
            'link' => $baseLink . $first,
            'page' => $first
        ];

        // All normal pages
        foreach ($pageNumbers as $number) {

            // Omit pages
            if ($number === false) {
                $result[] = [
                    'class' => 'missing'
                ];
            }
            
            // Normal page
            else {
                $result[] = [
                    'class' => ($current === $number) ? 'current' : 'adjacent',
                    'link' => $baseLink . $number,
                    'page' => $number
                ];
            }

        }

        // Last page
        $result[] = [
            'class' => 'last' . ($current === $last ? ' current' : ''),
            'link' => $baseLink . $last,
            'page' => $last
        ];

        // Next page
        $next = min($current + 1, $last);
        $result[] = [
            'class' => 'next',
            'disable' => $current === $next,
            'link' => $baseLink . $next,
            'page' => $next
        ];

        return $result;
    }

    private function buildLabel(): string
    {
        // Ex.: 1701 to 1725 of 3288
        return (
            $this->state['pagination']['lower-bound'].
            ' to '.$this->state['pagination']['upper-bound'].
            ' of '.$this->state['pagination']['total']
        );
    }
}
