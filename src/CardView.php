<?php

namespace App;

use App\Helpers;

class CardView
{
    public static $exclude = [
        'id',
        'backside',
        'narp',
        'image_path',
        'thumb_path',
        'rulings'
    ];

    /**
     * Adds a 'display' element to card arrays to show info on single card pages
     *
     * @param array $cards Reference to cards
     * @return void
     */
    public static function display(array &$cards): void
    {
        $cardFeatures = Helpers::get('cardfeatures');

        for ($i = 0, $ii = count($cards); $i < $ii; $i++) {

            $card =& $cards[$i];
            $labels = array_keys($card);
            $card['display'] = [];

            for ($j = 0, $jj = count($labels); $j < $jj; $j++) {

                $label =& $labels[$j];
                $value =& $card[$label];

                if (
                    !in_array($label, self::$exclude) &&
                    $value !== null &&
                    $value !== ''
                ) {
                    $card['display'][] = [
                        'label' => $cardFeatures[$label],
                        'value' => $value
                    ];
                }
            }
        }
    }
}
