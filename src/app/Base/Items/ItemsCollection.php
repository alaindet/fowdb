<?php

namespace App\Base\Items;

use App\Base\Items\Interfaces\ItemInterface;
use App\Base\Items\Interfaces\ItemsCollectionInterface;
use App\Base\Items\ItemsCollectionIteratorTrait;
use App\Base\Items\ItemsCollectionDsCollectionTrait;
use App\Base\Items\ItemsCollectionDataReadTrait;
use App\Base\Items\ItemsCollectionDataWriteTrait;
use App\Base\Items\ItemsCollectionListOperationsTrait;

class ItemsCollection implements ItemsCollectionInterface
{
    use ItemsCollectionIteratorTrait;
    use ItemsCollectionDsCollectionTrait;
    use ItemsCollectionDataReadTrait;
    use ItemsCollectionDataWriteTrait;
    use ItemsCollectionListOperationsTrait;

    protected $items = [];
}
