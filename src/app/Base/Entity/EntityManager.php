<?php

namespace App\Base\Entity;

use App\Base\Entity\EntityMapperInterface;
use App\Base\Entity\EntityRepositoryInterface;

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

    static function getMapper(string $entityClass): EntityMapperInterface
    {
        // Ex. App\Entity\Card\Card => App\Entity\Card\CardMapper
        $mapperClass = $entityClass . 'Mapper';

        if (isset(self::$mappers[$mapperClass])) {
            return self::$mappers[$mapperClass];
        }

        $mapper = new $mapperClass();
        self::$mappers[$mapperClass] = $mapper;

        return $mapper;
    }

    static function getRepository(string $entityClass): EntityRepositoryInterface
    {
        // Ex. App\Entity\Card\Card => App\Entity\Card\CardRepository
        $repositoryClass = $entityClass . 'Repository';

        if (isset(self::$repositories[$repositoryClass])) {
            return self::$repositories[$repositoryClass];
        }

        $repository = new $repositoryClass();
        $repository->entityClass = $entityClass; // IMPORTANT!
        self::$repositories[$repositoryClass] = $repository;

        return $repository;
    }
}
