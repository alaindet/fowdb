<?php

namespace App\Entity\GameFormat;

use App\Base\ORM\Entity\EntityCustomProperties;
use App\Base\Items\ItemsCollection;
use App\Base\ORM\Manager\EntityManager;

class GameFormatCustomProperties extends EntityCustomProperties
{
    protected $getters = [
        "clusters" => "getClustersProperty",
    ];

    public function getClustersProperty(bool $useCache): ItemsCollection
    {
        $repo = EntityManager::getRepository(get_class($this->entity));
        return $repo->getClusters($this->entity);
    }
}
