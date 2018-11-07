<?php

namespace App\Views\Card;

use App\Legacy\Helpers;
use App\Utils\Arrays;
use App\Utils\Strings;

class Card
{
    /**
     * Props to exclude on the 'display' element of cards
     *
     * @var array
     */
    public static $excludeDisplay = [
        'id',
        'back_side',
        'narp',
        'image_path',
        'thumb_path',
        'rulings'
    ];

    /**
     * Returns a list of a card's formats, ex.: [ [name, code], [name, code] ]
     * Based on its cluster
     * 
     * @param string $cluster This card's cluster
     * @return array Clusters of this card
     */
    public static function formatsListByCluster(string $cluster): array
    {
        $lookup = lookup('formats');
        $codeToName = $lookup['code2name'];
        $codeToClusters = $lookup['code2clusters'];

        // I need the array key, hence the custom Arrays::reduce
        return Arrays::reduce(
            
            // Data
            $codeToClusters,
            
            // Reducer
			function (
                $result,
                $formatClusters,
                $formatCode
            ) use (&$cluster, &$codeToName) {
				if (in_array($cluster, $formatClusters)) {
					$result[] = [
						'name' => $codeToName[$formatCode],
						'code' => $formatCode
					];
				}
				return $result;
            },
            
            // Reduced
            []
            
		);
    }

    /**
     * Builds a displayable HTML comma-separated list of formats links
     *
     * @param any $input Array => formats list, string => cluster
     * @return string HTML comma-separated list of <a>s to formats
     */
    public static function displayFormats($input): string
    {
        // Get formats list if user passed a cluster (a string)
        is_string($input)
            ? $formats = self::formatsListByCluster($input)
            : $formats =& $input;

        return implode(', ', Arrays::map($formats, function ($format) {
            return collapse(
                "<a href=\"/?do=search&format={$format['code']}\">",
                    $format['name'],
                "</a>"
            );
        }));
    }

    /**
     * Adds a 'display' element to card arrays to show info on card pages
     *
     * @param array $cards Reference to cards
     * @return void
     */
    public static function addDisplay(array &$cards)
    {
        foreach ($cards as &$card) {
            $display = [];
            foreach ($card as $key => $value) {
                if (
                    !in_array($key, self::$excludeDisplay) &&
                    $value !== null &&
                    $value !== ''
                ) {
                    $display[] = [
                        'label' => Strings::snakeToTitle($key),
                        'value' => $value
                    ];
                }
            }
            $card['display'] = $display;
        }

        // for ($i = 0, $ii = count($cards); $i < $ii; $i++) {

        //     $card =& $cards[$i];
        //     $labels = array_keys($card);
        //     $card['display'] = [];

        //     for ($j = 0, $jj = count($labels); $j < $jj; $j++) {

        //         $label =& $labels[$j];
        //         $value =& $card[$label];

        //         if (
        //             !in_array($label, self::$excludeDisplay) &&
        //             $value !== null &&
        //             $value !== ''
        //         ) {
        //             $card['display'][] = [
        //                 'label' => $features[$label],
        //                 'value' => $value
        //             ];
        //         }
        //     }
        // }
    }

    public static function removeIllegalProps(
        array &$card,
        string $type
    ): array
    {
        $toRemove = [
            'Ruler' => [ 'cost', 'total_cost', 'atk_def' ],
            'J-Ruler' => [ 'cost', 'total_ cost' ],
            'Resonator' => [],
            'Master Rune' => [ 'atk_def' ],
            'Chant' => [ 'atk_def' ],
            'Chant/Rune' => [ 'atk_def' ],
            'Addition' => [ 'atk_def' ],
            'Regalia' => [ 'atk_def' ],
            'Rune' => [ 'atk_def' ],
            'Magic Stone' => [ 'cost', 'total_cost', 'atk_def', 'attribute' ],
            'Special Magic Stone' => [
                'cost', 'total_cost', 'atk_def', 'attribute'
            ],
            'Special Magic Stone/True Magic Stone' => [
                'cost', 'total_cost', 'atk_def', 'attribute'
            ],
            'Spell:Chant' => [ 'atk_def' ],
            'Spell:Chant-Instant' => [ 'atk_def' ],
            'Spell:Chant-Standby' => [ 'atk_def' ],
            'Addition:Field' => [ 'atk_def' ],
            'Addition:J/Resonator' => [ 'atk_def' ],
            'Addition:Resonator' => [ 'atk_def' ],
            'Addition:Ruler/J-Ruler' => [ 'atk_def' ]
        ][$type];

        foreach ($card as $prop => &$value) {
            if (in_array($prop, $toRemove)) unset($card[$prop]);
        }

        return $card;
    }
}
