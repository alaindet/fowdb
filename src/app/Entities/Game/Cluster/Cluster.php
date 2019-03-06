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
     * Define computed properties and its property getters
     *
     * @var array
     */
    protected $propertyGetters = [

        'formats' => 'getFormatsProperty',
        'sets' => 'getSetsProperty',

    ];
}
