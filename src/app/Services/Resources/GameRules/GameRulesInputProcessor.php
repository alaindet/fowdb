<?php

namespace App\Services\Resources\GameRules;

use App\Base\InputProcessor;
use App\Services\Resources\GameRules\ManagesPostProcessing;

class GameRulesInputProcessor extends InputProcessor
{
    use ManagesPostProcessing;

    /**
     * Maps the input name to its processor function. Order is important
     *
     * @var array
     */
    protected $functions = [
        // 'txt-file' => 'processTxtFileInput',
        'version' => 'processVersionInput',
        'date-validity' => 'processDateValidityInput',
    ];

    // /**
    //  * On create: required
    //  * On update: optional
    //  *
    //  * @param array $value
    //  * @return void
    //  */
    // public function processTxtFileInput(array $value = null): void
    // {
    //     // Process file into
    // }

    public function processVersionInput(string $value = null): void
    {
        $this->new['version'] = $value;
    }

    public function processDateValidityInput(string $value = null): void
    {
        $this->new['date_validity'] = $value;
    }
}
