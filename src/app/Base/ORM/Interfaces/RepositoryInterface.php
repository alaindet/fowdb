<?php

namespace App\Base\ORM\Interfaces;

use App\Base\ORM\Interfaces\EntityInterface;
use App\Base\Items\Interfaces\ItemsCollectionInterface;
use App\Services\Database\Interfaces\HasPagination;
use App\Services\Database\Interfaces\PaginatorInterface;

interface RepositoryInterface extends HasPagination
{
    public function all(): ItemsCollectionInterface;
    public function findAllBy(string $field, $value): ItemsCollectionInterface;
    public function findBy(string $field, $value): ?EntityInterface;
    public function findById($id): ?EntityInterface;

    public function setPaginator(PaginatorInterface $paginator);
    public function getPaginator(): PaginatorInterface;
}
