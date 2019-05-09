<?php

namespace App\Base\Items;

use App\Base\Items\Interfaces\ItemsCollectionInterface;

/**
 * From App\Base\Items\ItemsCollection
 * ===================================
 * protected $items = [];
 */
trait ItemsCollectionListOperationsTrait
{
    /**
     * Flag to transform current collection instead of returning new ones
     *
     * @var bool
     */
    private $transformThisCollection = false;

    public function transformThisCollection(
        bool $transform = true
    ): ItemsCollectionInterface
    {
        $this->transformThisCollection = $transform;
        return $this;
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Performs a map operation on the items
     *
     * @param callable $callback
     * @return ItemsCollectionInterface
     */
    public function sort(callable $callback): ItemsCollectionInterface
    {
        $new = $this->items;
        if ($callback === null) {
            sort($new);
        } else {   
            usort($new, $callback);
        }

        if ($this->transformThisCollection) {
            $this->items = $new;
            return $this;
        }

        return (new ItemsCollection)->set($new);
    }

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

    /**
     * Performs a map operation on the items
     *
     * @param callable $callback
     * @return ItemsCollectionInterface
     */
    public function map(callable $callback): ItemsCollectionInterface
    {
        $new = [];
        for ($i = 0, $len = count($this->items); $i < $len; $i++) {
            $new[] = $callback($this->items[$i], $i);
        }

        if ($this->transformThisCollection) {
            $this->items = $new;
            return $this;
        }

        return (new ItemsCollection)->set($new);
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
     * Every new item is the value of extracted single column
     * 
     * Optionally transforms current collection instead of returning new one
     * 
     * Works *ONLY* on object items
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
        $new = [];
        for ($i = 0, $len = count($this->items); $i < $len; $i++) {
            $new[$i] = $this->items[$i]->$column;
        }

        if ($this->transformThisCollection) {
            $this->items = $new;
            return $this;
        }
        
        return (new ItemsCollection)->set($new);
    }

    /**
     * Extracts multiple values from all items by key name
     * Similar to pluck, but each item is a subset of the previous item
     * 
     * Works *ONLY* on object items
     *
     * @param array $columns
     * @return ItemsCollectionInterface
     */
    public function extract(array $columns): ItemsCollectionInterface
    {
        $newItems = [];
        for ($i = 0, $ii = count($this->items); $i < $ii; $i++) {
            $newItem = new \stdClass();
            for ($j = 0, $jj = count($columns); $j < $jj; $j++) {
                $column = &$columns[$j];
                $newItem->$column = $this->items[$i]->{$column};
            }
            $newItems[$i] = $newItem;
        }

        if ($this->transformThisCollection) {
            $this->items = $newItems;
            return $this;
        }

        return (new ItemsCollection)->set($newItems);
    }

    public function filter(callable $callback): ItemsCollectionInterface
    {
        $new = [];
        for ($i = 0, $len = count($this->items); $i < $len; $i++) {
            if ($callback($this->items[$i], $i)) {
                $new[] = $this->items[$i];
            }
        }

        if ($this->transformThisCollection) {
            $this->items = $new;
            return $this;
        }

        return (new ItemsCollection)->set($new);
    }
}
