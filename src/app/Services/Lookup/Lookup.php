<?php

namespace App\Services\Lookup;

use App\Base\Singleton;
use App\Services\Lookup\LookupCacheBuildTrait;
use App\Services\Lookup\LookupDataAccessTrait;
use App\Services\Lookup\Interfaces\LookupInterface;

/**
 * This library generates, reads and stores domain-specific data
 * Used throughout the application
 */
class Lookup implements LookupInterface
{
    use Singleton;
    use LookupDataAccessTrait;
    use LookupCacheBuildTrait;

    /**
     * List of available features
     *
     * @var array
     */
    protected $features = [
        'attributes',
        'backsides',
        'banned',
        'clusters',
        'costs',
        'divinities',
        'formats',
        'narps',
        'rarities',
        'sets',
        'sortables',
        'spoilers',
        'types',
    ];

    /**
     * Define the cache file location and load it
     */
    private function __construct()
    {
        $cacheFile = path_cache('lookup.txt');
        $this->setCacheFilePath($cacheFile);
        $this->load();
    }
}
