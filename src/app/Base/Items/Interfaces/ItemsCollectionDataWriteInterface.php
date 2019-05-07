<?php

namespace App\Base\Items\Interfaces;

use App\Base\Items\Interfaces\ItemsCollectionInterface;

interface ItemsCollectionDataWriteInterface
{
    public function set(array $items): ItemsCollectionInterface;
    public function add(ItemInterface $item): ItemsCollectionInterface;
}
