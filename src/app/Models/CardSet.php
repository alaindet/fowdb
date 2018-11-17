<?php

namespace App\Models;

use App\Base\Model;

class CardSet extends Model
{
    public $table = 'sets';

    public const TABLE = 'sets';

    public function getClusterById(string $setId): int
    {
        $data = database()
            ->select(
                statement('select')
                    ->select('clusters_id')
                    ->from(self::TABLE)
                    ->where('id = :setid')
                    ->limit(1)
            )
            ->from(self::TABLE)
            ->bind([
                ':setid' => $setId
            ])
            ->first();

        return (int) $data['clusters_id'];
    }
}
