<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Entities\Card\CardsRepository;
use App\Utils\Arrays;

class CardsController extends Controller
{
    public function propsHtml(Request $request): string
    {
        $code = 'RDE-009U';
        $props = [
            'html-name',
            'html-type',
            'html-cost',
            'html-total-cost',
            'html-battle-stats',
            'html-divinity',
            'html-race',
            'html-attribute',
            'html-text',
            'html-flavor-text',
            'html-code',
            'html-rarity',
            'html-artist',
            'html-set',
            'html-cluster',
            'html-format',
            'html-banned',
            'html-image',
        ];

        $repo = new CardsRepository;
        $cards = $repo->findAllByCode($code);

        // $count = $cards->count();
        // return "Cards are {$count}";

        $card = $cards->first();


        $html = "<ul>".Arrays::reduce(
            $props,
            function ($log, $propName) use ($card) {
                [$propLabel, $propValue] = $card->get($propName);
                return $log .= (
                    "<li>".
                        "<strong>{$propLabel}</strong> ({$propName})".
                        "<p>{$propValue}</p>".
                    "</li>"
                );
            },
            ""
        )."</ul>";

        return $html;
    }
}
