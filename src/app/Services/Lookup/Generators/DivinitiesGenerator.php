<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Interfaces\LookupDataGeneratorInterface;

class DivinitiesGenerator implements LookupDataGeneratorInterface
{
    public function generate(): array
    {
        return [0,1,2,3,4,5];
    }
}
