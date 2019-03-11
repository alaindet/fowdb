<?php

namespace App\Base\Items;

/**
 * Provides an implementation of the \Ds\Collection interface
 * to \App\Base\items\items
 * 
 * From \App\Base\items\items
 * ================================
 * private $items; // Array
 */
trait ItemsCollectionDsCollectionTrait
{
    public function clear(): void
    {
        $this->items = [];
    }

    public function copy(): \Ds\Collection
    {
        $collection = clone $this;
        return $collection;
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    public function isNotEmpty(): bool
    {
        return $this->items !== [];
    }

    public function toArray(): array
    {
        return $this->items;
    }
}
