<?php

namespace App\Base\Items\Interfaces;

use App\Base\Items\Interfaces\ItemsCollectionInterface;

interface ItemsCollectionListOperationsInterface
{
    public function count(): int;
    public function each(callable $callback): ItemsCollectionInterface;
    public function map(callable $callback, bool $transform): ItemsCollectionInterface;
    public function reduce(callable $callback, $carry);
    public function filter(callable $callback, bool $transform): ItemsCollectionInterface;
    public function pluck(string $column, bool $transform): ItemsCollectionInterface;
}
