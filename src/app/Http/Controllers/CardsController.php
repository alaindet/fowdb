<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Services\Resources\Card\Search\Search;
use App\Views\Page\Page;
use App\Services\Alert;
use App\Services\Configuration\Configuration;
use App\Http\Response\Redirect;

/**
 * Contains actions for PUBLIC routes only
 * Admin actions on cards are provided by ...\Admin\CardsController
 */
class CardsController extends Controller
{
    public function searchForm(Request $request): string
    {
        return (new Page)
            ->template("pages/public/cards/search/index-form")
            ->title("Cards Search")
            ->options([
                "scripts" => ["public/cards/search-form"]
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
        // return fd_log_html([
        //     "params" => $request->input()->get(),
        //     "sql" => $search->getStatement(),
        //     "bind" => $search->getBoundData(),
        // ]);

        $search->fetchResults();
        $results = $search->getResults();

        // ERROR: Cards not found!
        if (empty($results)) {
            Alert::add("No results. Please try changing your filters.", "danger");
            Redirect::to("cards/search");
        }

        return (new Page)
            ->template("pages/public/cards/search/index-results")
            ->title("Cards Search")
            ->options([
                "scripts" => ["public/cards/search-results"]
            ])
            ->variables([
                "results" => $results,
                "filters" => $search->getParameters(),
                "pagination" => $search->getPagination()
            ])
            ->render();
    }

    public function searchHelp(): string
    {
        return (new Page)
            ->template("pages/public/cards/search/index-help")
            ->title("Cards Search Help")
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
     * @param string $code The card"s code
     * @return string
     */
    public function show(Request $request, string $code): string
    {
        // Validate and process input
        // Ex.: ABC-001+C => ABC-001C
        $code = str_replace(" ", "", urldecode($code));

        $cardsRepository = new \App\Entities\Card\CardsRepository;
        $cards = $cardsRepository->findAllByCode($code);
        $card = $cards->first();

        // $cards = LegacyCard::getCardPageData($code);

        // Build Open Graph Protocol data for this page
        $card = $cards->first();
        $appName = (Configuration::getInstance())->get("app.name");
        $title = "{$card->get("name")} ({$card->get("code")}) ~ {$appName}";
        $ogp = [
            "title" => $title,
            "url" => $card->get("link"),
            "image" => [
                "url" => $card->get("thumb-path"),
                "alt" => $title
            ]
        ];

        // Find next card
        $nextCard = $cards->last()->getNext();
        
        return (new Page)
            ->template("pages/public/cards/show/index")
            ->title($title)
            ->variables([
                "cards" => $cards,
                "next_card" => $nextCard
            ])
            ->options([
                "ogp" => $ogp,
                "scripts" => ["public/cards/show"],
            ])
            ->render();
    }
}
