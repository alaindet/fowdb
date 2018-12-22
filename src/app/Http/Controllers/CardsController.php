<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Legacy\Card as LegacyCard;
use App\Models\Card as Model;
use App\Legacy\CardSearch as Search;
use App\Views\Page;

/**
 * Contains actions for PUBLIC routes only
 * Admin actions on cards are provided by ...\Admin\CardsController
 */
class CardsController extends Controller
{
    public function searchForm(Request $request): string
    {
        return view_old(
            'Search',
            'old/search/search.php',
            [ 'js' => [ 'public/search' ] ],
            ['thereWereResults' => false]
        );
    }

    public function search(Request $request): string
    {
        $search = new Search;

        // Read the raw input
        $input = $request->input()->get();

        // Filter out unwanted input
        $filters = $search->getFilters($input);

        // Get the results
        $cards = $search->processFilters($input)->getCards();

        // TEST: SQL statement
        // dump($search->getSQL());

        // ERROR: Cards not found!
        if (empty($cards)) {
            alert(
                'No results. Please try changing your searching criteria.',
                'danger'
            );
            redirect('cards/search');
        }

        // Alias the filters
        return view_old(
            'Search',
            'old/search/search.php',
            [ 'js' => [ 'public/search' ] ],
            [
                'filters' => $filters,
                'search' => $search,
                'cards' => $cards,
                'thereWereResults' => true
            ]
        );
    }

    public function showSearchHelp(): string
    {
        return (new Page)
            ->template('pages/public/cards/search-help/index')
            ->title('Cards Search Help')
            ->render();
    }

    /**
     * Shows a single card page
     * 
     * Accepts literal codes (also URL encoded), as well as "partial" code
     * (those who match the beginning of the code)
     * 
     * These codes are equivalent
     * NDR-002%20R
     * NDR-002+R
     * NDR-002
     *
     * @param Request $request
     * @param string $code The card's code
     * @return string
     */
    public function show(Request $request, string $code): string
    {
        // Validate and process input
        // Ex.: ABC-001+C => ABC-001C
        $code = str_replace(' ', '', urldecode($code));
        
        $cards = LegacyCard::getCardPageData($code);

        // Build Open Graph Protocol data for this page
        $card = &$cards[0];
        $title = "{$card['name']} ({$card['code']}) ~ ".config('app.name');
        $ogp = [
            'title' => $title,
            'url' => url('card/'.urlencode($card['code'])),
            'image' => [
                'url' => asset($card['thumb_path']),
                'alt' => $title
            ]
        ];

        // Calculate next card
        $lastCard = &$cards[count($cards)-1];
        $nextCard = (new Model)->getNext($lastCard['sorted_id']);
        
        return (new Page)
            ->template('pages/public/cards/show/index')
            ->title($title)
            ->variables([
                'cards' => $cards,
                'next_card' => $nextCard
            ])
            ->options([
                'ogp' => $ogp,
                'scripts' => [ 'public/card' ],
            ])
            ->render();
    }
}
