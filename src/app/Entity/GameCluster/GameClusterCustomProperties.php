<?php

namespace App\Entity\GameCluster;

use App\Base\ORM\Entity\EntityCustomProperties;
use App\Base\Items\ItemsCollection;
use App\Base\ORM\Manager\EntityManager;

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
