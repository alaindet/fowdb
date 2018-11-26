<?php

namespace App\Services\Set;

use App\Base\InputProcessor;
use App\Exceptions\CrudException;
use App\Services\Set\ManagesPostProcessing;

class SetInputProcessor extends InputProcessor
{
    use ManagesPostProcessing;

    protected $functions = [
        'cluster-id' => 'processClusterIdInput',
        'id' => 'processIdInput',
        'name' => 'processNameInput',
        'code' => 'processCodeInput',
        'count' => 'processCountInput',
        'release-date' => 'processReleaseDateInput',
        'is-spoiler' => 'processIsSpoilerInput',
    ];

    public function processClusterIdInput($value = null): void
    {
        $this->new['clusters_id'] = $value;
    }

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

    public function processCountInput($value = null): void
    {
        $this->new['count'] = $value;
    }

    public function processReleaseDateInput($value = null): void
    {
        $this->new['date_release'] = $value;
    }

    public function processIsSpoilerInput($value = null): void
    {
        $this->new['is_spoiler'] = $value;
    }
}
