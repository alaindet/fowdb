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

    public function add(ItemInterface $item): ItemsCollectionInterface
    {
        $this->items[] = $item;
        return $this;
    }

    public function pop()
    {
        return array_pop($this->items);
    }
}
