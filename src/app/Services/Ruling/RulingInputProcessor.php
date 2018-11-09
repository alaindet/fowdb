<?php

namespace App\Services\Ruling;

use App\Base\InputProcessor;

class RulingInputProcessor extends InputProcessor
{
    protected $functions = [
        'card-id' => 'processCardIdInput',
        'ruling-date' => 'processRulingDateInput',
        'ruling-errata' => 'processRulingErrataInput',
        'ruling-text' => 'processRulingTextInput',
    ];

    /**
     * Runs after all processors, useful to perform an action on shared state
     * Or set default values
     *
     * @return void
     */
    public function afterProcessing(): void
    {
        // Default ruling date
        if (!isset($this->new['date'])) $this->new['date'] = date('Y-m-d');

        // Default ruling errata flag
        if (!isset($this->new['is_errata'])) $this->new['is_errata'] = 0;
    }

    public function processCardIdInput($value = null)
    {
        $this->new['cards_id'] = $value;
    }

    public function processRulingDateInput($value = null)
    {
        if ($value !== '') $this->new['date'] = $value;
    }

    public function processRulingErrataInput($value = null)
    {
        if ($value !== '') $this->new['is_errata'] = $value;
    }

    public function processRulingTextInput($value = null)
    {
        $this->new['text'] = $value;
    }
}
