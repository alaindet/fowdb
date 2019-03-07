<?php

namespace App\Base\Items\Interfaces;

use App\Base\Items\Interfaces\ItemsCollectionInterface;

interface ItemsCollectionListOperationsInterface
{
    public function each(callable $callback): ItemsCollectionInterface;
    public function map(callable $callback): ItemsCollectionInterface;
    public function reduce(callable $callback, $carry = null); // mixed
    public function filter(callable $callback): ItemsCollectionInterface;
    public function pluck(string $column): ItemsCollectionInterface;
}
