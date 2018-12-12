<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Models\Card;

class CardsSortCommand extends Command
{
    public $name = 'cards:sort';

    public function run(array $options, array $arguments): void
    {
        Card::buildAllSortId();

        $this->message = 'Sorting ID for all cards has been regenerated.';
    }
}
