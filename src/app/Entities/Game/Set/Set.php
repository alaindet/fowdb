<?php

namespace App\Entities\Game\Set;

use App\Base\Entities\Entity;
use App\Entities\Game\Set\SetDatabasePropertiesTrait;
use App\Entities\Game\Set\SetComputedPropertiesTrait;

class Set extends Entity
{
    use SetDatabasePropertiesTrait;
    use SetComputedPropertiesTrait;

    /**
     * Define computed properties and its property getters
     *
     * @var array
     */
    protected $propertyGetters = [

        //

    ];
}
