<?php

namespace App\Base\Items\Interfaces;

use App\Base\Items\Interfaces\ItemsCollectionInterface;

interface ItemsCollectionDataWriteInterface
{
    public function set(array $items): ItemsCollectionInterface;
    public function add(ItemInterface $item): ItemsCollectionInterface;
    public function append($item): ItemsCollectionInterface;
    public function prepend($item): ItemsCollectionInterface;
    public function shift();
    public function pop();
    public function removeFirst();
    public function removeLast();
}
