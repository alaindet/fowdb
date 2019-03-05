<?php

namespace App\Base\Items;

use Iterator;
use App\Base\Items\ItemInterface;
use App\Base\Items\ItemsCollectionIteratorTrait;
use App\Base\Items\ItemsCollectionDsCollectionTrait;

abstract class ItemsCollection implements Iterator
{
    use ItemsCollectionIteratorTrait;
    use ItemsCollectionDsCollectionTrait;

    private $items = [];

    public function set(array $items): ItemsCollection
    {
        $this->items = $items;
        return $this;
    }

    public function get(int $index): ?ItemInterface
    {
        return $this->items[$index] ?? null;
    }

    public function first(): ?ItemInterface
    {
        $index = 0;
        return $this->get($index);
    }

    public function last(): ?ItemInterface
    {
        $index = count($this->items) - 1;
        return $this->get($index);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function each(callable $callback): ItemsCollection
    {
        for ($i = 0, $len = count($this->items); $i < $len; $i++) {
            $callback($this->items[$i], $i);
        }
        return $this;
    }

    public function map(callable $callback): ItemsCollection
    {
        for ($i = 0, $len = count($this->items); $i < $len; $i++) {
            $this->items[$i] = $callback($this->items[$i], $i);
        }
        return $this;
    }

    public function reduce(callable $callback, $carry = null)
    {
        if (!isset($carry)) {
            if ($this->items === []) {
                return null;
            }
            $carry = $this->items[0];
        }
        for ($i = 0, $len = count($this->items); $i < $len; $i++) {
            $carry = $callback($carry, $this->items[$i], $i);
        }
        return $carry;
    }
}
