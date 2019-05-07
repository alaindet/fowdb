<?php

namespace App\Base\Items;

use App\Base\Items\Interfaces\ItemInterface;
use App\Base\Items\Interfaces\ItemsCollectionInterface;

/**
 * From App\Base\Items\ItemsCollection
 * ===================================
 * protected $items = [];
 */
trait ItemsCollectionDataReadTrait
{
    public function get(int $index)
    {
        return $this->items[$index] ?? null;
    }

    public function first()
    {
        $index = 0;
        return $this->get($index);
    }

    public function last()
    {
        $index = count($this->items) - 1;
        return $this->get($index);
    }
}
