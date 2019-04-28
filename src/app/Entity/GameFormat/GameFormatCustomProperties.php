<?php

namespace App\Entity\GameFormat;

use App\Base\Entity\EntityCustomProperties;
use App\Base\Items\ItemsCollection;

class GameFormatCustomProperties extends EntityCustomProperties
{
    protected $getters = [
        "clusters" => "getClustersProperty",
    ];

    public function getClustersProperty(bool $useCache): ItemsCollection
    {
        return GameFormatRepository::getClusters($this->entity);
    }
}
