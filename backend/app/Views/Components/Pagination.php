<?php

namespace App\Views\Components;

use App\Views\Component;
use App\Exceptions\ViewComponentException;

class Pagination extends Component
{
    /**
     * Represents {src}/resources/views/components/pagination.tpl.php
     *
     * @var string
     */
    public $filename = 'pagination';

    /**
     * Returns page numbers to show
     * 
     * Calculates page numbers based on current page, last page and how many
     * page links you want to show
     *
     * @param integer $page
     * @param integer $lastPage
     * @param integer $count
     * @return array
     */
    private function pageNumbers(int $page, int $lastPage, int $count): array
    {
        $pages = [];

        // Pagination "radius" (integer casting because floor() returns double)
        $half = (int) floor($count / 2);

        $start = $page - $half;
        for ($i = $page - $half, $len = $start + $half; $i < $len; $i++) {
            $pages[] = $i;
        }

        // Build neighbour page numbers
        $pages = [];
        $start = $page - $half;
        for ($i = 0; $i < $count; $i++) $pages[] = $start++;

        // Fix left side
        while ($pages[0] < 1) {
            foreach ($pages as &$value) $value++;
        }

        // Radius too big?
        if ($lastPage < $count) $pages = array_slice($pages, 0, $lastPage);

        // Fix right side
        while ($pages[count($pages)-1] > $lastPage) {
            foreach ($pages as &$value) $value--;
        }

        return $pages;
    }

    private function buildLinks(array $pageNumbers): array
    {
        $result = [];
        $link =& $this->state['link'];
        $page =& $this->state['current-page'];
        $lastPage =& $this->state['last-page'];
        $baseLink = $link . ((strpos($link,'?')===false)?'?':'&') . 'page=';

        // Go to first page
        $result[] = [ 'link' => $baseLink . 1 ];

        foreach ($pageNumbers as $number) {
            $result[] = [
                'active' => ($number === $page),
                'link' => $baseLink . $number,
                'page' => $number
            ];
        }

        // Go to last page
        $result[] = [
            'link' => $baseLink . $lastPage,
            'page' => $lastPage
        ];

        return $result;
    }

    /**
     * Renders the HTML
     * 
     * Assumes these props set:
     * total, current-page, more,
     * lower-bound, upper-bound, page-numbers
     *
     * @return string
     */
    public function render(): string
    {
        $pageNumbers = $this->pageNumbers(
            $this->state['current-page'],
            $this->state['last-page'],
            $count = 5
        );

        $items = $this->buildLinks($pageNumbers);

        return $this->renderTemplate([ 'items' => $items ]);
    }
}
