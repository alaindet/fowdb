<?php

namespace App\Services\Resources\Cluster\Crud;

use App\Base\InputProcessor as BaseInputProcessor;
use App\Exceptions\CrudException;
use App\Services\Resources\Cluster\Crud\PostProcessingTrait;

class InputProcessor extends BaseInputProcessor
{
    use PostProcessingTrait;

    protected $functions = [
        'id' => 'processIdInput',
        'name' => 'processNameInput',
        'code' => 'processCodeInput',
    ];

    public function processIdInput($value = null): void
    {
        $this->new['id'] = $value;
    }

    public function processNameInput($value = null): void
    {
        $this->new['name'] = $value;
    }

    public function processCodeInput($value = null): void
    {
        $this->new['code'] = $value;
    }
}
