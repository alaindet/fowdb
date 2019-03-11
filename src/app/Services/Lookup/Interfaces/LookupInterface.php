<?php

namespace App\Services\Lookup\Interfaces;

use App\Services\Lookup\Interfaces\LookupDataAccessInterface;
use App\Services\Lookup\Interfaces\LookupCacheBuildInterface;

interface LookupInterface extends
    LookupDataAccessInterface,
    LookupCacheBuildInterface
{
    //
}
