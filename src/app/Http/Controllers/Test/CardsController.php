<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Entities\Card\CardsRepository;
use App\Utils\Arrays;
use App\Views\Page;

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
        $card = $repo->findByCode($code);

        $props = Arrays::reduce($props, function($result, $prop) use ($card) {
            [$propLabel, $propValue] = $card->get($prop);
            $result[$propLabel] = $propValue;
            return $result;
        }, []);

        return (new Page)
            ->template('test/cards/html-properties')
            ->title('Test: Card HTML properties')
            ->variables([
                'props' => $props
            ])
            ->minify(false)
            ->render();
    }
}
