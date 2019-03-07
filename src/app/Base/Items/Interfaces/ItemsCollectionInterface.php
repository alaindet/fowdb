<?php

namespace App\Base\Items\Interfaces;

use Iterator;
use App\Base\Items\Interfaces\ItemsCollectionDataAccessInterface;
use App\Base\Items\Interfaces\ItemsCollectionListOperationsInterface;

interface ItemsCollectionInterface extends
    Iterator,
    ItemsCollectionDataAccessInterface,
    ItemsCollectionListOperationsInterface
{
    public function count(): int;

    // From \Ds\Collection
    public function clear(): void;
    public function copy(); // \Ds\Collection;
    public function isEmpty(): bool;
    public function toArray(): array;
}
