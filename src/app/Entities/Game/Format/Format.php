<?php

namespace App\Entities\Game\Format;

use App\Base\Entities\Entity;
use App\Entities\Game\Format\DatabasePropertiesTrait;
use App\Entities\Game\Format\ComputedPropertiesTrait;

class Cluster extends Entity
{
    use DatabasePropertiesTrait;
    use ComputedPropertiesTrait;

    /**
     * Define computed properties and its property getters
     *
     * @var array
     */
    protected $propertyGetters = [

        'clusters' => 'getClustersProperty',

    ];
}
