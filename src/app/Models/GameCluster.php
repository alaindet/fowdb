<?php

namespace App\Models;

use App\Base\Model;

class GameCluster extends Model
{
    public $table = 'game_clusters';

    public static function nextAvailableId(): int
    {
        $item = fd_database()->rawSelect('SELECT max(id) max FROm game_clusters');

        return intval($item[0]['max']) + 1;
    }
}
