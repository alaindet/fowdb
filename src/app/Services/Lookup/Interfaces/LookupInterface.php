<?php

namespace App\Services\Lookup\Interfaces;

use App\Services\Lookup\Interfaces\LookupDataAccessInterface;
use App\Services\Lookup\Interfaces\LookupDataBuildInterface;

interface LookupInterface extends
    LookupDataAccessInterface,
    LookupDataBuildInterface
{
    //
}
