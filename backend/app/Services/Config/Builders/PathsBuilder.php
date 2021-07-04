<?php

namespace App\Services\Config\Builders;

use App\Services\Config\Builders\Builder;

class PathsBuilder extends Builder
{
    public function build(): array
    {
        $src = $this->getSrcPath();
        $public = dirname($src);

        return require "{$src}/data/app/config/paths.php";
    }
}
