<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;

class RacesController extends Controller
{
    private function getItems(string $what): array
    {
        $filter = [
            'races' => "type IN('Ruler', 'J-Ruler', 'Resonator')",
            'traits' => "NOT (type IN('Ruler','J-Ruler','Resonator'))"
        ][$what];

        $data = database()
            ->select(statement('select')
                ->select('DISTINCT race')
                ->from('cards')
                ->where($filter)
            )
            ->get();
        
        $items = [];
        foreach ($data as $item) {
            foreach (explode('/', $item['race']) as $value) {
                if (!isset($items[$value])) {
                    $items[$value] = 1;
                }
            }
        }
        $items = array_keys($items);
        sort($items);

        return $items;
    }

    public function index(Request $request): string
    {
        return (new Page)
            ->template('pages/public/races/index')
            ->title('Races and Traits')
            ->variables([
                'races' => $this->getItems('races'),
                'traits' => $this->getItems('traits')
            ])
            ->render();
    }
}
