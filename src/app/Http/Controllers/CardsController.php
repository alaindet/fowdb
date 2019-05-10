<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Models\Card as Model;
use App\Services\Resources\Card\Search\Search;
use App\Services\Resources\Card\Read\ReadService;
use App\Views\Page;

/**
 * Contains actions for PUBLIC routes only
 * Admin actions on cards are provided by ...\Admin\CardsController
 */
class CardsController extends Controller
{
    public function searchForm(Request $request): string
    {
        return (new Page)
            ->template('pages/public/cards/search/index-form')
            ->title('Cards Search')
            ->options([
                'scripts' => ['public/cards/search-form']
            ])
            ->render();
    }

    public function search(Request $request): string
    {
        $search = new Search;
        $search->setParameters($request->input()->get());
        $search->setPagination($request->getCurrentUrl());
        $search->processParameters();

        // // DEBUG
        // return log_html([
        //     'params' => $request->input()->get(),
        //     'sql' => $search->getStatement(),
        //     'bind' => $search->getBoundData(),
        // ]);

        $search->fetchResults();
        $results = $search->getResults();

        // ERROR: Cards not found!
        if (empty($results)) {
            fd_alert('No results. Please try changing your filters.', 'danger');
            redirect('cards/search');
        }

        return (new Page)
            ->template('pages/public/cards/search/index-results')
            ->title('Cards Search')
            ->options([
                'scripts' => ['public/cards/search-results']
            ])
            ->variables([
                'results' => $results,
                'filters' => $search->getParameters(),
                'pagination' => $search->getPagination()
            ])
            ->render();
    }

    public function searchHelp(): string
    {
        return (new Page)
            ->template('pages/public/cards/search/index-help')
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

        $cardsRepository = new \App\Entities\Card\CardsRepository;
        $cards = $cardsRepository->findAllByCode($code);
        $card = $cards->first();

        dump($card);

        // $cards = LegacyCard::getCardPageData($code);

        // Build Open Graph Protocol data for this page
        $card = $cards->first();
        $appName = config('app.name');
        $title = "{$card->get('name')} ({$card->get('code')}) ~ {$appName}";
        $ogp = [
            'title' => $title,
            'url' => $card->get('link'),
            'image' => [
                'url' => $card->get('thumb-path'),
                'alt' => $title
            ]
        ];

        // Find next card
        $nextCard = $cards->last()->getNext();

        // // Build Open Graph Protocol data for this page
        // $card = &$cards[0];
        // $title = "{$card['name']} ({$card['code']}) ~ ".config('app.name');
        // $ogp = [
        //     'title' => $title,
        //     'url' => url('card/'.urlencode($card['code'])),
        //     'image' => [
        //         'url' => asset($card['thumb_path']),
        //         'alt' => $title
        //     ]
        // ];

        // // Calculate next card
        // $lastCard = &$cards[count($cards)-1];
        // $nextCard = (new Model)->getNext($lastCard['sorted_id']);
        
        return (new Page)
            ->template('pages/public/cards/show/index')
            ->title($title)
            ->variables([
                'cards' => $cards,
                'next_card' => $nextCard
            ])
            ->options([
                'ogp' => $ogp,
                'scripts' => ['public/cards/show'],
            ])
            ->render();
    }
}
