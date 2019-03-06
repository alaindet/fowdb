<?php

namespace App\Entities\Play\Format;

use App\Base\Entities\Entity;
use App\Entities\Play\Format\FormatDatabasePropertiesTrait;
use App\Entities\Play\Format\FormatComputedPropertiesTrait;

class Format extends Entity
{
    use FormatDatabasePropertiesTrait;
    use FormatComputedPropertiesTrait;

    /**
     * Define computed properties and its property getters
     *
     * @var array
     */
    protected $propertyGetters = [
        'clusters' => 'getClustersProperty',
    ];

    /**
     * Define computed properties and its property setters
     *
     * @var array
     */
    protected $propertySetters = [
        'clusters' => 'setClustersProperty',
    ];
}
