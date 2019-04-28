<?php

namespace App\Entity\GameCluster;

use App\Base\Entity\Entity\EntityCustomProperties;
use App\Base\Items\ItemsCollection;
use App\Base\Entity\Manager\EntityManager;

class GameClusterCustomProperties extends EntityCustomProperties
{
    protected $getters = [
        "formats" => "getFormatsProperty",
    ];

    public function getFormatsProperty(bool $useCache): ItemsCollection
    {
        $repo = EntityManager::getRepository(get_class($this->entity));
        return $repo->getFormats($this->entity);
    }
}
