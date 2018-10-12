<?php

namespace App\Views\Ban;

class Ban
{
    /**
     * Adds some display props to cards: link
     *
     * @param array $items
     * Ex.: [ format => [ [name=>,code=>,image=>,format_code=>], .. ], .. ]
     * @return array
     */
    public function display(array &$items): array
    {
        foreach ($items as &$cards) {
            foreach ($cards as &$card) {
                $card['link'] = url('card', [
                    'code' => str_replace(' ', '+', $card['code'])
                ]);
            }
        }

        return $items;
    }
}
