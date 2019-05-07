<?php

namespace App\Base\Items\Interfaces;

use Iterator;
use App\Base\Items\Interfaces\ItemsCollectionDataReadInterface;
use App\Base\Items\Interfaces\ItemsCollectionDataWriteInterface;
use App\Base\Items\Interfaces\ItemsCollectionListOperationsInterface;

interface ItemsCollectionInterface extends
    Iterator,
    ItemsCollectionDataReadInterface,
    ItemsCollectionDataWriteInterface,
    ItemsCollectionListOperationsInterface
{
    // From \Ds\Collection
    public function clear(): void;
    public function copy(); // \Ds\Collection;
    public function isEmpty(): bool;
    public function toArray(): array;
}
