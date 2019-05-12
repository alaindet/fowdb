<?php

namespace App\Entity\GameCluster\Write;

use App\Base\ORM\Write\WriteService;
use App\Entity\GameCluster\Write\InputMapper;
use App\Base\ORM\Manager\EntityManager;
use App\Entity\GameCluster\GameCluster;
use App\Services\Configuration\Configuration;
use App\Base\ORM\Write\WriteServiceInterface;
use App\Services\FileSystem\FileSystem;

class CreateService extends WriteService
{
    protected $inputMapper = InputMapper::class;
    protected $validationRules = [
        "id" => [
            "required",
            "is:integer",
            "except:0",
            "!exists:game_clusters,id"
        ],
        "name" => [
            "required",
            "is:text",
            "!empty"
        ],
        "code" => [
            "required",
            "is:alphadash",
            "!empty",
            "!exists:game_clusters,code"
        ],
    ];
    protected $cacheFeatures = [
        "banned",
        "clusters",
        "formats",
    ];

    public function create(): WriteServiceInterface
    {
        $this->validate();
        $this->processInput();
        $this->writeOnDatabase();
        $this->writeOnFileSystem();
        $this->updateCache();
        return $this;
    }

    public function writeOnDatabase(): WriteServiceInterface
    {
        $repo = EntityManager::getRepository(GameCluster::class);
        $repo->storeEntity($this->new);

        return $this;
    }

    public function writeOnFileSystem(): WriteServiceInterface
    {
        $config = Configuration::getInstance();
        $sizes = explode(",", $config->get("dir.resolutions"));
        $format = fd_path_root("images/cards/%s/{$this->new->code}");

        foreach ($sizes as $size) {
            FileSystem::createDirectory(sprintf($format, $size));
        }

        return $this;
    }

    public function getFeedback(): array
    {
        $message = (
            "New cluster <strong> ".
                "#{$this->new->id} {$this->new->name} ({$this->new->code})".
            "</strong> created."
        );

        return [$message, ''];
    }
}
