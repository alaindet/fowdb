<?php

namespace App\Services\Config\Builders;

use App\Services\Config\Builders\Builder;

class TimestampsBuilder extends Builder
{
    public function build(): array
    {
        $src = $this->getSrcPath();
        return require "{$src}/data/app/config/timestamps.php";
    }
}
