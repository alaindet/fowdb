<?php

namespace App\Models;

use App\Base\Model;

class CardType extends Model
{
    public $table = 'card_types';

    /**
     * Game-specific: maps current types to old types or other types too
     *
     * @var array
     */
    public static $equivalents = [
        'Addition' => [
            'Addition:Resonator',
            'Addition:J/Resonator',
            'Addition:Ruler/J-Ruler',
            'Addition:Field',
        ],
        'Rune' => [
            'Chant/Rune'
        ],
        'Chant' => [
            'Spell:Chant',
            'Spell:Chant-Instant',
            'Spell:Chant-Standby',
            'Chant/Rune',
        ]
    ];

    public static $withRace = [
        'Ruler',
        'J-Ruler',
        'Resonator'
    ];
}
