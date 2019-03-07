<?php

namespace App\Base\Items\Interfaces;

use App\Base\Items\Interfaces\ItemInterface;
use App\Base\Items\Interfaces\ItemsCollectionInterface;

interface ItemsCollectionDataAccessInterface
{
    // Setters
    public function set(array $items): ItemsCollectionInterface;
    public function add(ItemInterface $item): ItemsCollectionInterface;

    // Getters
    public function get(int $index): ?ItemInterface;
    public function first(): ?ItemInterface;
    public function last(): ?ItemInterface;
}
