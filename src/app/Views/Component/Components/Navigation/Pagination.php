<?php

namespace App\Views\Component\Components\Navigation;

use App\Views\Component\AbstractComponent;
use App\Base\Items\ItemsCollection;
use App\Base\Items\Interfaces\ItemsCollectionInterface;

/**
 * INPUT
 * object pagination {
 *   int totalCount
 *   int count
 *   int page
 *   int lastPage
 *   int lowerBound
 *   int upperBound
 *   string link
 *   bool hasMorePages
 *   bool hasAnyPagination
 * }
 * bool hasLabel
 * string[] css [ class, class, ... ]
 * 
 * TEMPLATE VARIABLES
 * App\Base\Items\ItemsCollection links
 * int progressPercentage
 * string progressLabel
 */
class Pagination extends AbstractComponent
{
    public $templateName = "navigation/pagination";

    protected function process(): void
    {
        $info = $this->input->pagination;
        $page = $info->page;
        $lastPage = $info->lastPage;

        $pageNumbers = $this->buildPageNumbers($page, $lastPage, $diameter = 5);

        $this->templateVars->links = $this->buildLinks($pageNumbers);

        $this->templateVars->progressLabel = $this->buildLabel();

        $this->templateVars->progressPercentage = (
            number_format(100 * $page / $lastPage, 0)
        );

        (isset($this->input->css))
            ? $this->templateVars->css = " ".implode(" ", $this->input->css)
            : $this->templateVars->css = "";
    }

    /**
     * Returns page numbers to show
     * 
     * Calculates page numbers based on current page, last page and how many
     * page links you want to show (the "diameter")
     * 
     * Returned array can have false values on first and last elements to 
     * represent other pages not represented in the diameter
     * 
     * Ex.:
     * 100 total pages
     * current page is 8
     * diameter is 3 (so 3 items shown, including the current page)
     * $pages = [false,7,8,9,false];
     *
     * @param integer $current Current page number
     * @param integer $last Total number of pages
     * @param integer $diameter How many items to show (with current, no ends)
     * @return array
     */
    private function buildPageNumbers(
        int $currentPage,
        int $lastPage,
        int $diameter
    ): array
    {
        $pages = [];
        $pagesCount = 0;
        $firstPage = 1;

        $radius = (int) floor($diameter / 2);

        ($currentPage - $radius <= $firstPage)
            ? [$lower, $lowerMissing] = [$firstPage + 1, false]
            : [$lower, $lowerMissing] = [$currentPage - $radius, true];

        ($currentPage + $radius >= $lastPage)
            ? [$upper, $upperMissing] = [$lastPage - 1, false]
            : [$upper, $upperMissing] = [$currentPage + $radius, true];

        for ($i = $lower; $i <= $upper; $i++) {
            $pages[] = $i;
            $pagesCount++;
        }

        if ($lowerMissing && $pages[0] > $firstPage + 1) {
            array_unshift($pages, false);
        }

        if ($upperMissing && $pages[$pagesCount - 1]) {
            $pages[] = false;
        }

        return $pages;
    }

    /**
     * Builds the collection of link objects to display on the template
     * 
     * Each link object has a "class" property to classify it
     * Classes are (? means optional)
     * [ ?prev, first, ?missing, ?adjacent, current, last, ?next]
     * 
     * link object properties
     * - class
     * - ?isDisabled
     * - url
     * - page
     *
     * @param array $pageNumbers
     * @return ItemsCollectionInterface
     */
    private function buildLinks(array $pageNumbers): ItemsCollectionInterface
    {
        $links = [];
        $info = &$this->input->pagination;
        $firstPage = 1;
        $currentPage = $info->page;
        $lastPage = $info->lastPage;
        $url = $info->link;
        $baseUrl = $url . ((strpos($url,"?") === false) ? "?" : "&") . "page=";

        $previousPage = max($currentPage - 1, $firstPage);
        $links[] = (object) [
            "class" => "prev",
            "isDisabled" => ($currentPage === $previousPage),
            "url" => $baseUrl . $previousPage,
            "page" => $previousPage
        ];

        $links[] = (object) [
            "class" => "first" . ($currentPage === $firstPage ? " current" : ""),
            "url" => $baseUrl . $firstPage,
            "page" => $firstPage
        ];

        foreach ($pageNumbers as $pageNumber) {

            if ($pageNumber === false) {
                $links[] = (object) [
                    "class" => "missing"
                ];
                continue;
            }

            $links[] = (object) [
                "class" => ($currentPage === $pageNumber)
                    ? "current"
                    : "adjacent",
                "url" => $baseUrl . $pageNumber,
                "page" => $pageNumber,
            ];
        }

        $links[] = (object) [
            "class" => "last" . ($currentPage === $lastPage ? " current" : ""),
            "url" => $baseUrl . $lastPage,
            "page" => $lastPage
        ];

        $nextPage = min($currentPage + 1, $lastPage);
        $links[] = (object) [
            "class" => "next",
            "isDisabled" => ($currentPage === $nextPage),
            "url" => $baseUrl . $nextPage,
            "page" => $nextPage
        ];

        return (new ItemsCollection())->set($links);
    }

    /**
     * Builds the label shown into the template, if needed
     *
     * @return string
     */
    private function buildLabel(): string
    {
        $showLabel = $this->input->hasLabel ?? true;

        if (!$showLabel) {
            return "";
        }

        $p = &$this->input->pagination;
        return "{$p->lowerBound} to {$p->upperBound} of {$p->totalCount}";
    }
}
