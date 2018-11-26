<?php

namespace App\Services\Resources\Cluster;

use App\Base\InputProcessor;
use App\Exceptions\CrudException;
use App\Services\Resources\Cluster\ManagesPostProcessing;

class ClusterInputProcessor extends InputProcessor
{
    use ManagesPostProcessing;

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
