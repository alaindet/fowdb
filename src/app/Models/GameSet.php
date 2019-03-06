<?php

namespace App\Models;

use App\Base\Model;

class GameSet extends Model
{
    public $table = 'game_sets';

    public static function nextAvailableId(): int
    {
        $item = database()->rawSelect('SELECT max(id) max FROM game_sets');

        return intval($item[0]['max']) + 1;
    }
}
