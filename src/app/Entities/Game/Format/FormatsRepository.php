<?php

namespace App\Entities\Game\Format;

use App\Entities\Game\Format\Format;
use App\Entities\Game\Format\Formats;
use App\Base\Entities\Repository;

class FormatsRepository extends Repository
{
    private $table = 'game_formats';
    private $entityClass = Format::class;
    private $entitiesClass = Formats::class;
}
