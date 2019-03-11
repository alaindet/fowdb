<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Interfaces\LookupDataGeneratorInterface;

class CostsGenerator implements LookupDataGeneratorInterface
{
    public function generate(): array
    {
        return [0,1,2,3,4,5,6,7,8,9,10];
    }
}
