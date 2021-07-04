<?php

namespace App\Services\Resources\GameRuling\Crud;

use App\Base\InputProcessor as BaseInputProcessor;

class InputProcessor extends BaseInputProcessor
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

    public function processCardIdInput($value = null): void
    {
        $this->new['cards_id'] = $value;
    }

    public function processRulingDateInput($value = null): void
    {
        if ($value !== '') $this->new['date'] = $value;
    }

    public function processRulingErrataInput($value = null): void
    {
        if ($value !== '') $this->new['is_errata'] = $value;
    }

    public function processRulingTextInput($value = null): void
    {
        $this->new['text'] = $value;
    }
}
