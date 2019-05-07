<?php

namespace App\Base\Items;

use App\Base\Items\Interfaces\ItemInterface;

/**
 * Provides an implementation of the \Iterator interface
 * to \App\Base\Items\ItemsCollection
 * 
 * From \App\Base\Items\ItemsCollection
 * ================================
 * private $items; // Array
 */
trait ItemsCollectionIteratorTrait
{
    private $cursor = 0;

    public function rewind(): void
    {
        $this->cursor = 0;
    }

    public function next(): void
    {
        $this->cursor = $this->cursor + 1;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->cursor]);
    }

    public function current()
    {
        return $this->items[$this->cursor];
    }

    public function key(): int
    {
        return $this->cursor;
    }
}
