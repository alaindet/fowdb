<?php

namespace App\Entities\Game\Format;

trait DatabasePropertiesTrait
{
    public $id;
    public $name;
    public $code;
    public $desc;
    public $is_default;
    public $is_multi_cluster;
}
