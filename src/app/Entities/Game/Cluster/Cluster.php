<?php

namespace App\Entities\Game\Cluster;

use App\Base\Entities\Entity;
use App\Entities\Game\Cluster\ClusterDatabasePropertiesTrait;
use App\Entities\Game\Cluster\ClusterComputedPropertiesTrait;

class Cluster extends Entity
{
    use ClusterDatabasePropertiesTrait;
    use ClusterComputedPropertiesTrait;

    /**
     * Define computed properties and its property accessors
     *
     * @var array
     */
    protected $propertyAccessors = [

        'formats' => 'getFormatsProperty',
        'sets' => 'getSetsProperty',

    ];
}
