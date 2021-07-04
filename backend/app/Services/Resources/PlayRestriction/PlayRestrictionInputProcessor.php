<?php

namespace App\Services\Resources\PlayRestriction;

use App\Base\InputProcessor;

class PlayRestrictionInputProcessor extends InputProcessor
{
    protected $functions = [
        'card-id' => 'processCardIdInput',
        'format-id' => 'processFormatIdInput',
        'deck' => 'processDeckInput',
        'copies' => 'processCopiesInput',
    ];

    public function processCardIdInput($value = null): void
    {
        $this->new['cards_id'] = $value;
    }

    public function processFormatIdInput($value = null): void
    {
        $this->new['formats_id'] = $value;
    }

    public function processDeckInput($value = null): void
    {
        $this->new['deck'] = $value;
    }

    public function processCopiesInput($value = null): void
    {
        $this->new['copies'] = $value;
    }
}
