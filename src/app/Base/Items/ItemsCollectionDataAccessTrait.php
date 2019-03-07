<?php

namespace App\Base\Items;

use App\Base\Items\Interfaces\ItemInterface;
use App\Base\Items\Interfaces\ItemsCollectionInterface;

/**
 * Implements App\Base\Items\Interfaces\ItemsCollectionDataAccessInterface
 * for App\Base\Items\ItemsCollection
 * 
 * From App\Base\Items\ItemsCollection
 * ===================================
 * protected $items = [];
 */
trait ItemsCollectionDataAccessTrait
{
    public function set(array $items): ItemsCollectionInterface
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
}
