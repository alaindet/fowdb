<?php

namespace App\Entity\GameCluster\Write;

use App\Base\InputMapper as BaseInputMapper;
use App\Entity\GameCluster\GameCluster;

class InputMapper extends BaseInputMapper
{
    protected $mappers = [
        'id' => 'mapIdInput',
        'name' => 'mapNameInput',
        'code' => 'mapCodeInput',
    ];

    public function __construct()
    {
        $this->setNewEntity(new GameCluster);
    }

    public function mapIdInput($value): void
    {
        $this->new->id = $value;
    }

    public function mapNameInput($value): void
    {
        $this->new->name = $value;
    }

    public function mapCodeInput($value): void
    {
        $this->new->code = $value;
    }
}
