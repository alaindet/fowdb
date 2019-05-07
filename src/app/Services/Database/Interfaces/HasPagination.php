<?php

namespace App\Services\Database\Interfaces;

use App\Services\Database\Interfaces\PaginatorInterface;

interface HasPagination
{
    public function setPaginator(PaginatorInterface $paginator);
    public function getPaginator(): PaginatorInterface;
}
