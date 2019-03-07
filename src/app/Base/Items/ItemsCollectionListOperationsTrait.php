<?php

namespace App\Base\Items;

use App\Base\Items\Interfaces\ItemsCollectionInterface;

/**
 * Implements App\Base\Items\Interfaces\ItemsCollectionListOperationsInterface
 * for App\Base\Items\ItemsCollection
 * 
 * From App\Base\Items\ItemsCollection
 * ===================================
 * protected $items = [];
 */
trait ItemsCollectionListOperationsTrait
{
    /**
     * Performs a callback on every item
     * If callback returns FALSE, the loop stops
     *
     * @param callable $callback
     * @return ItemsCollection
     */
    public function each(callable $callback): ItemsCollectionInterface
    {
        for ($i = 0, $len = count($this->items); $i < $len; $i++) {
            if ($callback($this->items[$i], $i) === false) {
                return $this;
            }
        }
        return $this;
    }

    public function map(callable $callback): ItemsCollectionInterface
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

    /**
     * Loops on all items and extract a single column from every item
     * Each item becomes the value of the extracted column
     * 
     * Ex.:
     * items: [ ['a' => 1], ['a' => 2], ['a' => 3] ]
     * pluck('a')
     * items:  [ 1, 2, 3 ]
     *
     * @param string $column
     * @return ItemsCollection
     */
    public function pluck(string $column): ItemsCollectionInterface
    {
        for ($i = 0, $len = count($this->items); $i < $len; $i++) {
            $this->items[$i] = $this->items[$i]->$column;
        }
        return $this;
    }

    public function filter(callable $callback): ItemsCollectionInterface
    {
        $filtered = [];
        for ($i = 0, $len = count($this->items); $i < $len; $i++) {
            if ($callback($this->items[$i], $i)) {
                $filtered[] = $this->items[$i];
            }
        }
        $this->items = $filtered;
        return $this;
    }
}
