<?php

namespace App\Base\Items;

use App\Base\Items\Interfaces\ItemInterface;
use App\Base\Items\Interfaces\ItemsCollectionInterface;
use App\Base\Items\ItemsCollectionIteratorTrait;
use App\Base\Items\ItemsCollectionDsCollectionTrait;
use App\Base\Items\ItemsCollectionDataAccessTrait;
use App\Base\Items\ItemsCollectionListOperationsTrait;

class ItemsCollection implements ItemsCollectionInterface
{
    use ItemsCollectionIteratorTrait;
    use ItemsCollectionDsCollectionTrait;
    use ItemsCollectionDataAccessTrait;
    use ItemsCollectionListOperationsTrait;

    protected $items = [];

    public function count(): int
    {
        return count($this->items);
    }
}
