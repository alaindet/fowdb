<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Models\Card;

class CardsSortCommand extends Command
{
    public $name = "cards:sort";

    public function run(): Command
    {
        Card::buildAllSortId();

        $this->setMessage(
            "Sorting ID (cards.sorted_id) for all cards has been regenerated"
        );

        return $this;
    }
}
