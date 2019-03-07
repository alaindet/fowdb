<?php

namespace App\Base\Items\Interfaces;

use App\Base\Items\Interfaces\ItemInterface;
use App\Base\Items\Interfaces\ItemsCollectionInterface;

interface ItemsCollectionDataAccessInterface
{
    public function set(array $items): ItemsCollectionInterface;
    public function get(int $index): ?ItemInterface;
    public function first(): ?ItemInterface;
    public function last(): ?ItemInterface;
}
