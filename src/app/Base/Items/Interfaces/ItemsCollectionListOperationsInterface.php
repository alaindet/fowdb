<?php

namespace App\Base\Items\Interfaces;

use App\Base\Items\Interfaces\ItemsCollectionInterface;

interface ItemsCollectionListOperationsInterface
{
    public function count(): int;
    public function each(callable $callback): ItemsCollectionInterface;
    public function map(callable $callback): ItemsCollectionInterface;
    public function reduce(callable $callback, $carry);
    public function filter(callable $callback): ItemsCollectionInterface;
    public function pluck(string $column): ItemsCollectionInterface;
    public function sort(callable $callback): ItemsCollectionInterface;
    public function transformThisCollection(bool $transform = true): ItemsCollectionInterface;
}
