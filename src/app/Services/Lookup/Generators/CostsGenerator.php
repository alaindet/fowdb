<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;

class CostsGenerator implements Generatable
{
    public function generate(): array
    {
        return [0,1,2,3,4,5,6,7,8,9,10];
    }
}
