<?php

namespace App\Services\Card;

use App\Base\InputProcessor;

class CardInputProcessor extends InputProcessor
{
    protected $functions = [
        // 'set' => 'processSetInput',
        // ...
    ];

    /**
     * Runs after all processors, useful to perform an action on shared state
     * Or set default values
     *
     * @return void
     */
    public function afterProcessing(): void
    {
        //
    }

    public function processSetInput($value = null)
    {
        $map = lookup('sets.code2id');
        $this->new['sets_id'] = $map[$value];
    }
}
