<?php

namespace App\Services\Lookup;

use App\Base\Singleton;
use App\Services\Lookup\LookupDataBuildTrait;
use App\Services\Lookup\LookupDataAccessTrait;
use App\Services\Lookup\Interfaces\LookupInterface;
use App\Utils\Paths;

/**
 * This library generates, reads and stores domain-specific data
 * Used throughout the application
 */
class Lookup implements LookupInterface
{
    use Singleton;
    use LookupDataAccessTrait;
    use LookupDataBuildTrait;

    /**
     * List of available features
     *
     * @var array
     */
    protected $features = [
        "attributes",
        "backsides",
        "banned",
        "clusters",
        "costs",
        "divinities",
        "formats",
        "narps",
        "rarities",
        "sets",
        "sortables",
        "spoilers",
        "types",
    ];

    /**
     * All lookup data is stored here
     *
     * @var object
     */
    protected $data;

    /**
     * Define the cache file location and load it
     */
    private function __construct()
    {
        $this->setCacheFilePath(Paths::inCacheDir("lookup.json"));
        $this->load();
    }
}
