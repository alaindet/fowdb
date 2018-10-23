<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;

class DivinitiesGenerator implements Generatable
{
    public function generate(): array
    {
        return [0,1,2,3,4,5];
    }
}
