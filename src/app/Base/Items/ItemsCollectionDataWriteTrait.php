<?php

namespace App\Base\Items;

use App\Base\Items\Interfaces\ItemInterface;
use App\Base\Items\Interfaces\ItemsCollectionInterface;

/**
 * From App\Base\Items\ItemsCollection
 * ===================================
 * protected $items = [];
 */
trait ItemsCollectionDataWriteTrait
{
    public function set(array $items): ItemsCollectionInterface
    {
        $this->items = $items;
        return $this;
    }

    public function add($item): ItemsCollectionInterface
    {
        $this->items[] = $item;
        return $this;
    }

    public function append($item): ItemsCollectionInterface
    {
        $this->add($item);
    }

    public function prepend($item): ItemsCollectionInterface
    {
        array_unshift($this->items, $item);
        return $this;
    }

    public function shift()
    {
        return array_unshift($this->items);
    }

    /**
     * Alias of $this->shift()
     *
     * @return void
     */
    public function removeFirst()
    {
        return array_unshift($this->items);
    }

    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * Alias of $this->pop()
     *
     * @return void
     */
    public function removeLast()
    {
        return array_pop($this->items);
    }
}
