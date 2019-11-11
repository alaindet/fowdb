<?php

namespace App\Views\Card;

use App\Legacy\Helpers;
use App\Utils\Arrays;
use App\Utils\Strings;
use App\Models\Card as Model;
use App\Utils\Bitmask;

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
        'rulings',
        'sorted_id',
        'is_banned',
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
        $formatCodeToName = lookup('formats.code2name');
        $formatCodeToClusters = lookup('formats.code2clusters');

        $result = [];

        foreach ($formatCodeToClusters as $code => $clusters) {
            if (in_array($cluster, $clusters)) {
                $result[] = [
                    "name" => $formatCodeToName[$code],
                    "code" => $code,
                ];
            }
        }

        return $result;
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
            $link = url('cards', [ 'format' => [$format['code']] ]);
            return "<a href=\"{$link}\">{$format['name']}</a>";
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
    }

    public static function removeIllegalProps(
        array &$card,
        string $type
    ): array
    {
        $removables = (new Model)->getRemovableFields();
        $bitmask = (new Bitmask)->setMask(intval($type));

        // Remove costs
        foreach ($removables['no-cost'] as $type) {
            if (!$bitmask->hasBitValue($type)) continue;
            unset($card['cost']);
            unset($card['total_cost']);
            unset($card['attribute_cost']);
            break;
        }

        // Remove attribute
        foreach ($removables['no-attribute'] as $type) {
            if (!$bitmask->hasBitValue($type)) continue;
            unset($card['attribute_bit']);
            break;
        }

        // Remove divinity
        $removeDivinity = false;
        foreach ($removables['can-divinity'] as $type) {
            if ($bitmask->hasBitValue($type)) $removeDivinity = false;
        }
        if ($removeDivinity) unset($card['divinity']);

        // Remove ATK and DEF
        $removeAtkDef = true;
        foreach ($removables['can-battle'] as $type) {
            if ($bitmask->hasBitValue($type)) $removeAtkDef = false;
        }
        if ($removeAtkDef) unset($card['atk_def']);

        return $card;
    }

    public static function buildTypeLabels(int $typeMask)
    {
        $bitmask = (new Bitmask)->setMask($typeMask);
        $labels = [];

        foreach (lookup('types.display') as $label => $bitval) {
            if ($bitmask->hasBitValue($bitval)) $labels[] = $label;
        }

        return $labels;
    }
}
