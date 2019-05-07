<?php

namespace App\Base\ORM\Manager;

use App\Base\ORM\Interfaces\EntityMapperInterface;
use App\Base\ORM\Interfaces\RepositoryInterface;
use App\Base\ORM\Interfaces\EntityMetaDataInterface;
use App\Base\ORM\MetaData\EntityMetaData;

class EntityManager
{
    /**
     * Stores existing instances of entity mappers for later use
     *
     * @var array
     */
    static private $mappers = [];

    /**
     * Stores existing instances of entity repositories for later use
     *
     * @var array
     */
    static private $repositories = [];

    /**
     * Stores existing meta data objects for later use
     *
     * @var array
     */
    static private $metaData = [];

    static public function getMapper(
        string $entityClass
    ): EntityMapperInterface
    {
        // Return existing instance
        if (isset(self::$mappers[$entityClass])) {
            return self::$mappers[$entityClass];
        }

        // Ex. App\Entity\Card\Card => App\Entity\Card\CardMapper
        $mapperClass = $entityClass. "Mapper";
        $mapper = new $mapperClass();
        self::$mappers[$mapperClass] = $mapper;

        return $mapper;
    }

    static public function getRepository(
        string $entityClass
    ): RepositoryInterface
    {
        // Return existing instance
        if (isset(self::$repositories[$entityClass])) {
            return self::$repositories[$entityClass];
        }

        // Ex. App\Entity\Card\Card => App\Entity\Card\CardRepository
        $repositoryClass = $entityClass . "Repository";
        $repository = new $repositoryClass();
        self::$repositories[$repositoryClass] = $repository;

        return $repository;
    }

    static public function getMetaData(
        string $entityClass
    ): EntityMetaDataInterface
    {
        // Return existing instance
        if (isset(self::$metaData[$entityClass])) {
            return self::$metaData[$entityClass];
        }

        $repo = self::getRepository($entityClass);
        $metaData = new EntityMetaData;

        $metaData->entityClass = $repo->entityClass;
        $metaData->table = $repo->table;
        $metaData->fields = array_keys(get_class_vars($entityClass));
        $metaData->foreignKey = $repo->foreignKey ?? null;
        $metaData->relationships = $repo->relationships ?? null;

        self::$metaData[$entityClass] = $metaData;
        return $metaData;
    }
}
