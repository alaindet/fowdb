<?php

namespace App\Base\Entity\Repository;

use App\Base\Entity\Entity\EntityInterface;
use App\Base\Items\Interfaces\ItemsCollectionInterface;

interface EntityRepositoryInterface
{
    public function all(): ItemsCollectionInterface;
    public function findAllBy(string $field, $value): ItemsCollectionInterface;
    public function findBy(string $field, $value): ?EntityInterface;
    public function findById($id): ?EntityInterface;
}