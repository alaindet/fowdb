<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Entities\Card\CardsRepository;
use App\Utils\Arrays;
use App\Views\Page;

class CardsController extends Controller
{
    public function propsHtml(Request $request, string $code = null): string
    {
        $code = $code ?? 'RDE-022U';
        $props = [
            'html_name',
            'html_type',
            'html_cost',
            'html_total-cost',
            'html_battle-values',
            'html_divinity',
            'html_race',
            'html_attribute',
            'html_text',
            'html_flavor-text',
            'html_code',
            'html_rarity',
            'html_artist',
            'html_set',
            'html_cluster',
            'html_format',
            'html_banned',
            'html_image',
        ];

        $repo = new CardsRepository;
        $card = $repo->findByCode($code);

        $props = Arrays::reduce(

            // Data
            $props,
            
            // Reducer
            function($result, $propName) use ($card) {
                $prop = $card->get($propName);
                if ($prop !== null) {
                    [$propLabel, $propValue] = $card->get($propName);
                    $result[$propLabel] = $propValue;    
                }
                return $result;
            },

            // State
            []

        );

        return (new Page)
            ->template('test/cards/html-properties')
            ->title('Test: Card HTML properties')
            ->variables([
                'code' => $code,
                'props' => $props
            ])
            ->minify(false)
            ->render();
    }

    public function typesList(): string
    {
        $filter = 'total_cost IS NULL';

        $data = fd_database()
            ->select(
                statement('select')
                    ->select(['type_bit', 'COUNT(*) as count'])
                    ->from('cards')
                    ->where($filter)
                    ->groupBy('type_bit')
                    ->orderBy(['count DESC', 'type_bit DESC'])
            )
            ->get();

        return log_html(
            $data[0]
            // $data[0]->type_names
        );


        $log = \App\Utils\Arrays::reduce($data,
            function($result, $item) {
                $total += $item->count;
                $bitmask = new \App\Utils\Bitmask;
                $bitmask->setMask($item->type_bit);
                $labels = [];
                foreach (lookup('types.display') as $label => $bitval) {
                    if ($bitmask->hasBitValue($bitval)) {
                        $labels[] = $label;
                    }
                }
                $labelsString = implode(', ', $labels);
                $result .= (
                    "<p>Count: {$item->count}, Type(s): {$labelsString}</p>"
                );
                return $result;
            },
            [
                "message" => "<h1>FILTER: {$filter}</h1>",
                "total" => 0
            ]
        );

        return $log . "<p>Total: {$total}</p>";
    }
}
